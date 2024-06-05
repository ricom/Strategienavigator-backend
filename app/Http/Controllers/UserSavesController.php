<?php

namespace App\Http\Controllers;

use App\Helper\PermissionHelper;
use App\Http\Resources\SimpleSaveResource;
use App\Models\Save;
use App\Models\SharedSave;
use App\Models\User;
use App\Policies\SavePolicy;
use App\Policies\SharedSavePolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller, eine Route zum Anzeigen aller Speicherstände eins zugehörigen Users
 * @package App\Http\Controllers
 */
class UserSavesController extends Controller
{
    /**
     * Gibt alle Speicherstände (geteilte und die, die im Besitzt sind) aus
     * @param Request $request Die aktuelle Request instanz
     * @param User $user Der User, welcher in der Route definiert wurde
     * @return AnonymousResourceCollection Alle Speicherständer, nach der Filterung
     * @throws AuthorizationException Wenn der User keine Berechitung hat sich die Speicherstände des Users anzuschauen
     * @see Save
     * @see SharedSavePolicy
     * @see
     */
    public function index(Request $request, User $user)
    {
        $this->authorize("viewOfUser", [SharedSave::class, $user]);
        $validated = $request->validate([
            "tool_id" => ["integer", "exists:tools,id"],
            "name" => ["string"],
            "description" => ["string"],
            "deleted" => ["sometimes", "boolean"],
            "search_both" => ["boolean"]
        ]);

        if (!key_exists("search_both", $validated)) {
            $validated["search_both"] = false;
        }

        $savesQuerry = $user->saves();
        $contributorSavesQuery = $user->accessibleShares(true);

        $where = function ($query) use ($validated) {
            if (key_exists("tool_id", $validated)) {
                $query->where("tool_id", $validated["tool_id"]);
            }
            if (!key_exists("deleted", $validated)) {
                $query->where("deleted_at", null);
            }
            if (key_exists("name", $validated)) {
                $query->where("name", "Like", "%" . $validated["name"] . "%");
            }
            if (key_exists("description", $validated)) {
                if ($validated["search_both"]) {
                    $query->where("description", "Like", "%" . $validated["description"] . "%");
                } else {
                    $query->orWhere("description", "Like", "%" . $validated["description"] . "%");
                }
            }
        };

        $savesQuerry->where($where);
        // add because of missing pivot table data which would make the union fail
        $savesQuerry->select([
            "saves.*",
            'pivot_user_id' => DB::raw("NULL"),
            'pivot_save_id' => DB::raw("NULL"),
            'pivot_permission' => DB::raw("NULL"),
            'pivot_created_at' => DB::raw("NULL"),
            'pivot_updated_at' => DB::raw("NULL")
        ]);
        $contributorSavesQuery->where($where);


        $saves = $contributorSavesQuery->union($savesQuerry->getBaseQuery())->paginate(null, []/* empty array to prevent duplicate "saves.*" select*/);

        // remove previously added null pivot columns
        foreach ($saves->items() as $save) {
            if ($save->pivot->permission == null) {
                $save->unsetRelation('pivot');
            }
        }

        return SimpleSaveResource::collection($saves);
    }

    /**
     * Gibt die zuletzt geöffneten Speicherstände des gegeben Users zurück.
     *
     * Speicherstände die nicht für den aktuell authentifizierten User sichbar sind, werden ausgeblendet.
     *
     * @param Request $request Der Request
     * @param User $user Der gegeben User
     * @return AnonymousResourceCollection Maximal 4 SimpleSaveResource
     * @throws AuthorizationException Wenn der aktuell authentifizierte User nicht berechtigt ist, die Speicherstände des gegeben Nutzers anzuschauen.
     */
    public function indexLast(Request $request, User $user)
    {

        $this->authorize("viewLast", $user);

        $authenticatedUser = $request->user();

        $lastOpenedSaves = $user->lastOpenedSavesDesc()->limit(4)->get();

        $visibleSaves = collect();

        /** @var Save $save */
        foreach ($lastOpenedSaves as $save) {
            // only the authenticatedUser is checked, because he can see, that the given user did at some point in time opened the given save.
            if ($save->hasAtLeasPermission($authenticatedUser, PermissionHelper::$PERMISSION_READ)) {
                $visibleSaves->add($save);
            }
        }

        return SimpleSaveResource::collection($visibleSaves);
    }
}
