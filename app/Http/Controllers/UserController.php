<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\EmailVerification;
use App\Models\User;
use App\Services\EmailService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use League\OAuth2\Server\AuthorizationServer;

class UserController extends Controller
{

    private static $passwordRegex = "/^(?=.*[a-zäöüß])(?=.*[A-ZÄÖÜ])(?=.*\d)(?=.*[$&§+,:;=?@#|'<>.^*()%!_-])[A-Za-zäöüßÄÖÜ\d$&§+,:;=?@#|'<>.^*()%!_-].+$/";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $this->authorize("viewAny", User::class);
        return UserResource::collection(User::with(['saves', 'invitedSaves', 'accessibleShares'])->whereNotNull("email_verified_at")->simplePaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function store(Request $request, EmailService $emailService, UserService $userService): Response
    {
        $validated = \Validator::validate($request->all(), [
            "username" => ["required", "string", "unique:users"],
            "password" => [ "required", "string", "min:8", "max:120", "regex:" . UserController::$passwordRegex],
            "email" => ["required", "email", "unique:users,email", "unique:" . EmailVerification::class . ",email"]
        ], [
            "password.regex" => __("passwords.invalid_regex")
        ]);

        $u = new User();
        $userService->updateUser($u, array_merge(["anonym" => false,], $validated), $emailService);
        return \response()->created('users', $u);
    }


    public function storeAnonymous()
    {
        $password = md5(microtime());
        $u = $this->createAnonymousUser($password);
        $u->save();

        return \response()->created('users', $u)->setContent(["username" => $u->username, "password" => $password]);
    }

    /**
     * @param User $u the anonymous user which gets upgraded
     * @param array $data array with username, password and email fields
     * @param EmailService $emailService
     */
    private function upgradeAnonymousUser(User $u, array $data, EmailService $emailService)
    {
        if ($u->anonym) {
            $u->anonym = false;
            $u->fill($data);
            $u->password = $data["password"];
            $emailService->requestEmailChangeOfUser($u, $data["email"]);
            $u->save();
        }
    }

    /**
     * @return User
     * @throws \Exception
     */
    private function createAnonymousUser(string $password)
    {
        $u = new User();
        $u->anonym = true;
        $u->password = $password;
        $u->last_activity = Carbon::now();
        do {
            $u->username = "anonymous" . random_int(1000, 1000000); // TODO maybe change username generation method (could end up in infinitive loop)
        } while (User::whereUsername($u->username)->exists());

        return $u;
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        $this->authorize("view", $user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request, User $user, EmailService $emailService, UserService $userService): Response
    {
        $this->authorize("update", $user);

        $validated = \Validator::validate($request->all(), [
            "username" => ["string", "unique:users"],
            "password" => ["string", "min:8", "max:120", "regex:" . UserController::$passwordRegex],
            // "email" => ["email", "unique:users,email", "unique:" . EmailVerification::class . ",email"]
        ], [
            "password.regex" => __("passwords.invalid_regex")
        ]);

        $userService->updateUser($user, $validated, $emailService);
        return response()->noContent(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user): Response
    {
        $this->authorize("delete", $user);
        $user->delete();
        return response()->noContent(Response::HTTP_OK);
    }

    public function checkUsername(Request $request, UserService $userService): JsonResponse
    {
        $validated = $request->validate([
            "username" => ["string", "required"]
        ]);
        return response()->json(["data" => [
            "available" => $userService->checkUsername($validated["username"])
        ]]);

    }

    public function checkEmail(Request $request, UserService $userService): JsonResponse
    {
        $validated = $request->validate([
            "email" => ["string", "required"]
        ]);
        return response()->json(["data" => [
            "available" => $userService->checkEmail($validated["email"])
        ]]);

    }
}
