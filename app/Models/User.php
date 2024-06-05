<?php

namespace App\Models;

use App\Traits\Limitable;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

/**
 * App\Models\User
 *
 * @property int $id Id des Users
 * @property string $username Benutzername des Users
 * @property string|null $last_activity Zeitpunkt der letzten aktivität
 * @property string $email Verifizierte E-Mail des Users
 * @property Carbon|null $email_verified_at Zeitpunkt an dem die E-Mail verifiziert wurde
 * @property string $password Passwort des Users, gehasht und gesalted
 * @property bool $anonymous Ob das Konto ein anonymes ist oder nicht
 * @property Carbon|null $deleted_at Zeitpunkt, an dem der User gelöscht wurde
 * @property Carbon|null $created_at Zeitpunkt, an dem der User erstellt wurde, bei einem vorherigen anonymen Konto ist es der Zeitpunkt als das Konto hochgestuft wurde
 * @property Carbon|null $updated_at Zeitpunkt, an dem der User das letzte man geändert wurde
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereJoinedAt($value)
 * @method static Builder|User whereLastLogin($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User whereLastActivity($value)
 * @method static Builder|User whereAnonymous($value)
 * @method static Builder|User whereDeletedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Save[] $accessibleShares alle geteilten Speicherstände, auf die der User Zugriff hat
 * @property-read EmailVerification|null $emailVerification die zum User gehörenden EmailVerification einträge
 * @property-read Collection|SharedSave[] $invitations
 * @property-read Collection|Save[] $invitedSaves alle geteilten Speicherstände, welche noch nicht revoked sind und noch nicht angenommen
 * @property-read Collection|Save[] $isLocking alle Speicherstände die von diesem User gesperrt werden
 * @property-read Collection|Save[] $saves alle Speicherstände die diesem User gehören
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read int|null $invitations_count
 * @property-read int|null $invited_saves_count
 * @property-read int|null $is_locking_count
 * @property-read int|null $saves_count
 * @property-read Collection|\App\Models\SharedSave[] $sharedSaves
 * @property-read int|null $shared_saves_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 */
class User extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes, Limitable;

    /**
     * Attribute, welche Massen zuweisbar sind
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'description'
    ];

    /**
     * Attribute, welche beim Konvertieren in ein array, nicht in das Array hinzugefügt werden
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'last_activity',
    ];

    /**
     * Zugehörigkeit, welche Attribute zu welchen nativen Typen gecastet werden soll.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_activity' => 'datetime',
        'anonymous' => 'boolean'
    ];

    /**
     * Findet die User instanz des übergebenen Usernamen oder E-Mail
     *
     * @param string $username E-Mail oder Benutzername, E-Mail bei nicht anonymen Nutzern und Benutzername bei anonymen Nutzern
     * @return User the user, could be an anonymous user or a normal one
     */
    public function findForPassport(string $username)
    {
        //TODO User nicht als return type hinzufügen, da query null returnen könnte und dann eine exception geworfen wird, die passport nicht erwartet
        return $this->withTrashed()->where('email', $username)->where('anonymous', false)
            ->orWhere('username', $username)->where('anonymous', true)
            ->first();
    }

    /**
     * Setter des Passwort attributes
     *
     * Hashed das passwort
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = Hash::make($value);
    }

    /**
     * Beschreibt die Beziehung zu den Speicherständen die diesem User gehören
     *
     * @return HasMany
     */
    public function saves(): HasMany
    {
        return $this->hasMany(Save::class, 'owner_id');
    }

    /**Beschreibt die Beziehung zu den Speicherständen die von diesem User gesperrt werden
     * @return HasMany
     */
    public function isLocking(): HasMany
    {
        return $this->hasMany(Save::class, 'locked_by_id');
    }

    /**Beziehung zu der EmailVerification Tabelle
     * @return HasOne
     */
    public function emailVerification(): HasOne
    {
        return $this->hasOne(EmailVerification::class);
    }

    /**
     * Beziehung zu den Speicherständen, zu denen der User eingeladen wurde aber diese noch nicht angenommen hat
     * @return BelongsToMany
     */
    public function invitedSaves(): BelongsToMany
    {
        return $this->belongsToMany(Save::class, 'shared_save')->using(SharedSave::class)
            ->withPivot(["permission"])
            ->withPivotValue("accepted", false)
            ->withPivotValue("declined", false)
            ->withPivotValue("revoked", false)
            ->withTimestamps();
    }

    /**
     * Alle SharedSave Einträge, die noch nicht revoked oder angenomemn sind
     * @return HasMany
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(SharedSave::class)
            ->where('accepted', '=', false)
            ->where('declined', '=', false)
            ->where('revoked', '=', false);
    }

    /**
     * Alle SharedSave Einträge die zu diesem User gehören
     * @return HasMany
     */
    public function sharedSaves(): HasMany
    {
        return $this->hasMany(SharedSave::class);
    }

    public function lastOpenedSavesDesc(): BelongsToMany
    {
        return $this->belongsToMany(Save::class, LastVisitedSaves::class)
            ->withPivot("visited_at")
            ->orderByPivot("visited_at", "desc");
    }

    /**
     * Ale Speicherstände zu den dieser User Zugriff hat
     * @param bool $withPivot Ob die Pivot Tabelle mitgeladen werden soll
     * @return BelongsToMany
     */
    public function accessibleShares(bool $withPivot = true): BelongsToMany
    {
        $q = $this->belongsToMany(Save::class, 'shared_save')->using(SharedSave::class)
            ->withPivotValue("accepted", true)
            ->withPivotValue("declined", false)
            ->withPivotValue("revoked", false)
            ->select('saves.*');

        if ($withPivot) {
            $q
                ->withPivot(["permission"])
                ->withTimestamps();
        }


        return $q;
    }

    public function settings($setting_id = -1): BelongsToMany
    {
        $q = $this->belongsToMany(Setting::class, 'user_settings')->withPivot(['id', 'value']);
        if ($setting_id !== -1) {
            $q->where('setting_id', $setting_id);
        }

        return $q;
    }

    public function getUserSetting(int $setting_id): \Illuminate\Database\Eloquent\Model|HasMany
    {
        return $this->hasMany(UserSetting::class)->where("setting_id", "=", $setting_id)->firstOrFail();
    }


}
