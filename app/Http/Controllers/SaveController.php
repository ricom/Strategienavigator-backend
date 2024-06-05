<?php

namespace App\Http\Controllers;

use App\Events\LiveSaveUpdate;
use App\Http\Resources\SaveResource;
use App\Http\Resources\SimpleSaveResource;
use App\Models\LastVisitedSaves;
use App\Models\Save;
use App\Models\User;
use App\Policies\SavePolicy;
use App\Services\SaveResourceService;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;

/**
 * Controller, welcher Routen zum Verwalten von Speicherständen implementiert
 * @package App\Http\Controllers
 */
class SaveController extends Controller
{
    const ALLOWED_MIMETYPES = [
        // convertable image types by gd lib
        "image/jpeg",
        "image/png",
        "image/bmp",
        "image/vnd.wap.wbmp",
        "image/webp",
        "image/svg+xml",
        "application/json",
        "application/pdf",
        "text/plain",
        "text/csv",
        "text/html",
        "text/plain",
        "application/vnd.oasis.opendocument.spreadsheet",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/vnd.oasis.opendocument.text",
        "application/vnd.oasis.opendocument.presentation"];
    const FILE_MAX_KILOBYTES = 5 * 1024;

    public function __construct(private SaveResourceService $resourceService)
    {

    }


    /**
     * Zeigt alle Speicherstände an
     * @return AnonymousResourceCollection Speicherstände als ResourceCollection
     * @throws AuthorizationException Wenn der User keine Berechtigung zum Ansehen aller Speicherstände hat
     * @see Save
     * @see SavePolicy
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize("viewAny", Save::class);

        return SimpleSaveResource::collection(Save::with("contributors")->paginate());
    }

    /** Erstellt einen neuen Speicherstand
     * @param Request $request Die aktuelle Request instanz
     * @return JsonResponse Code 201, wenn der Speicherstand erfolgreich erstellt wurde
     * @throws AuthorizationException Wenn der User keine Berechtigung zum Erstellen von Speicherständen hat
     * @see Save
     * @see SavePolicy
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize("create", Save::class);

        $validate = $request->validate([
            "name" => "required|string|max:255",
            "description" => "string|max:300",
            "data" => "nullable|json",
            "tool_id" => "required|exists:tools,id",
            "resources" => ["sometimes", "array"],
            "resources.*.file" => [
                File::types(self::ALLOWED_MIMETYPES)
                    ->max(self::FILE_MAX_KILOBYTES)
            ],
            "resources.*.name" => [
                "string"
            ]
        ]);
        $s = DB::transaction(function () use ($request, $validate) {
            $s = new Save($validate);
            $s->tool_id = $validate["tool_id"];
            $s->owner_id = $request->user()->id;
            $s->save();

            if (array_key_exists("resources", $validate)) {
                $this->resourceService->updateResources($s, $validate["resources"]);
            }
            return $s;
        });

        return response()->json(new SaveResource($s), 201);
    }

    /**
     * Gibt den ausgewählten Speicherstand zurück
     *
     * Das <code>last_opened</code> Attribut wird auf die aktuelle Zeit gesetzt.
     *
     * @param Save $save Der in der Url definierte Speicherstand
     * @return SaveResource Die Resource des in der Url definierten Speicherstandes
     * @throws AuthorizationException Wenn der User keine Berechtigung zum Anschauen des Speicherstandes hat
     * @see Save
     * @see SavePolicy
     * @see Save::$last_opened
     */
    public function show(Request $request, Save $save): SaveResource
    {
        $this->authorize("view", $save);
        /**
         * @var User $user
         */
        $user = $request->user();
        $save->last_opened = Carbon::now();
        $user->lastOpenedSavesDesc()->syncWithoutDetaching([$save->id => ['visited_at' => Carbon::now()]]);

        if ($save->isContributor($user)) {
            $save->setRelation('pivot', $user->sharedSaves()->where('save_id', $save->id)->first());
        }
        $save->save();

        return new SaveResource($save);
    }

    /**
     * @throws AuthorizationException
     */
    public function broadcastPatches(Request $request, Save $save): Response
    {
        $this->authorize("broadcast", $save);
        $validate = $request->validate([
            "data" => "required|string",
        ]);
        $patches = $validate["data"];

        broadcast(new LiveSaveUpdate($request->user(), $save, $patches))->toOthers();

        return response()->noContent(Response::HTTP_OK);
    }

    /** Aktualisiert den ausgewählten Speicherstand mit den übergebenen Daten
     *
     *  Response-Codes:
     *  - 200: Änderungen übernomen
     *  - 424: Speicherstand muss vorher gesperrt werden
     *  - 423: Speicherstand ist gerade von einem anderen User gesperrt
     *
     * @param Request $request Die aktuelle Request instanz
     * @param Save $save Der in der Url definierte Speicherstand
     * @return Response Gibt einen passenden Response-Code zurück
     * @throws AuthorizationException Wenn der User keine Berechtigung hat den Speicherstand zu überschreiben
     * @see Save
     * @see SavePolicy
     */
    public function update(Request $request, Save $save): Response
    {
        $this->authorize("update", $save);
        $user = $request->user();

        if ($request->has("lock")) {

            $validated = $request->validate([
                "lock" => "required|boolean",
                "data" => "prohibited",
                "name" => "prohibited",
                "description" => "prohibited",
                "resources" => "prohibited"
            ]);

            if ($validated["lock"]) {
                $save->locked_by_id = $user->id;
                $save->last_locked = Carbon::now();
            } else {
                $save->locked_by_id = null;
            }

            $save->save();
            return response()->noContent(Response::HTTP_OK);
        } else {
            $validated = $request->validate([
                "data" => "nullable|json",
                "name" => "string|max:255",
                "description" => "string|max:300",
                "resources" => ["sometimes", "required", "array"],
                "resources.*.file" => [
                    File::types(self::ALLOWED_MIMETYPES)
                        ->max(self::FILE_MAX_KILOBYTES)
                ],
                "resources.*.name" => ["sometimes", "required", "string"],
                "lock" => "prohibited"
            ]);

            return DB::transaction(function () use ($validated, $save, $user) {
                if ($save->locked_by_id === $user->id) {
                    $save->fill($validated);
                    $save->save();

                    if (array_key_exists("resources", $validated)) {
                        $this->resourceService->updateResources($save, $validated["resources"]);
                    }
                    return response()->noContent(Response::HTTP_OK);
                } else {
                    return response()->noContent(Response::HTTP_LOCKED);
                }
            });

        }
    }

    /**
     * Löschten den ausgewählten Speicherstand
     * @param Save $save Den Speicherstand, welcher in der Url definiert wurde
     * @return Response code 200, wenn der Speicherstand gelöscht wurde
     * @throws AuthorizationException Wenn der User keine Berechtigung besitzt den Speicherstand zu löschen
     */
    public function destroy(Save $save): Response
    {
        $this->authorize("delete", $save);
        $save->delete();
        return response()->noContent(Response::HTTP_OK);
    }
}
