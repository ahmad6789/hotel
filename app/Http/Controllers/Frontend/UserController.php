<?php

namespace App\Http\Controllers\Frontend;

use App\Authorizable;
use App\Events\Frontend\UserProfileUpdated;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Labrere;
use App\Models\Permission;
use App\Models\RewardOrPunishment;
use App\Models\Role;
use App\Models\User;
use App\Models\Userprofile;
use App\Models\UserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Log;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Users';

        // module name
        $this->module_name = 'users';

        // directory path of the module
        $this->module_path = 'users';

        // module icon
        $this->module_icon = 'fas fa-users';

        // module model name, path
        $this->module_model = "App\Models\User";
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($username)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $$module_name_singular = $module_model::where('username', 'LIKE', $username)->first();

        $body_class = 'profile-page';

        $meta_page_type = 'profile';

        return view(
            "frontend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'body_class', 'meta_page_type')
        );
    }

    /**
     * Display Profile Details of Logged in user.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'Profile';

        $$module_name_singular = $module_model::findOrFail($id);

        if ($$module_name_singular) {
            $userprofile = Userprofile::where('user_id', $id)->first();
        } else {
            Log::error('UserProfile Exception for Username: ' . $username);
            abort(404);
        }

        $body_class = 'profile-page';

        $meta_page_type = 'profile';

        return view("frontend.$module_name.profile", compact('module_name', 'module_name_singular', "$module_name_singular", 'module_icon', 'module_action', 'module_title', 'body_class', 'userprofile', 'meta_page_type'));
    }

    /**
     * Show the form for Profile Paeg Editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function profileEdit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit Profile';

        $page_heading = ucfirst($module_title);
        $title = $page_heading . ' ' . ucfirst($module_action);

        if (!auth()->user()->can('edit_users')) {
            $id = auth()->user()->id;
        }

        if ($id != auth()->user()->id) {
            return redirect()->route('frontend.users.profile', $id);
        }

        $$module_name_singular = $module_model::findOrFail($id);
        $userprofile = Userprofile::where('user_id', $id)->first();

        $body_class = 'profile-page';

        return view(
            "frontend.$module_name.profileEdit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'userprofile', 'body_class')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request, $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'Profile Update';

        if ($id != auth()->user()->id) {
            return redirect()->route('frontend.users.profile', $id);
        }

        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $module_name = $this->module_name;
        $module_name_singular = Str::singular($this->module_name);

        if (!auth()->user()->can('edit_users')) {
            $id = auth()->user()->id;
            $username = auth()->user()->username;
        }

        $$module_name_singular = $module_model::findOrFail($id);
        $filename = $$module_name_singular->avatar;

        // Handle Avatar upload
        if ($request->hasFile('avatar')) {
            if ($$module_name_singular->getMedia($module_name)->first()) {
                $$module_name_singular->getMedia($module_name)->first()->delete();
            }

            $media = $$module_name_singular->addMedia($request->file('avatar'))->toMediaCollection($module_name);

            $$module_name_singular->avatar = $media->getUrl();

            $$module_name_singular->save();
        }

        $data_array = $request->except('avatar');
        $data_array['avatar'] = $$module_name_singular->avatar;
        $data_array['name'] = $request->first_name . ' ' . $request->last_name;

        $user_profile = Userprofile::where('user_id', '=', $$module_name_singular->id)->first();
        $user_profile->update($data_array);

        event(new UserProfileUpdated($user_profile));

        return redirect()->route('frontend.users.profile', $$module_name_singular->id)->with('flash_success', 'Update successful!');
    }

    /**
     * Show the form for Profile Paeg Editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'change Password';

        $body_class = 'profile-page';

        if ($id != auth()->user()->id) {
            return redirect()->route('frontend.users.profile', $id);
        }

        $id = auth()->user()->id;

        $$module_name_singular = $module_model::findOrFail($id);

        $body_class = 'profile-page';

        return view("frontend.$module_name.changePassword", compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'body_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function changePasswordUpdate(Request $request, $username)
    {
        if ($id != auth()->user()->id) {
            return redirect()->route('frontend.users.profile', $id);
        }

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $module_name = $this->module_name;
        $module_name_singular = Str::singular($this->module_name);

        $$module_name_singular = auth()->user();

        $request_data = $request->only('password');
        $request_data['password'] = Hash::make($request_data['password']);

        $$module_name_singular->update($request_data);

        return redirect()->route('frontend.users.profile', auth()->user()->id)->with('flash_success', 'Update successful!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'Edit';

        if ($id != auth()->user()->id) {
            return redirect()->route('frontend.users.profile', $id);
        }

        $roles = Role::get();
        $permissions = Permission::select('name', 'id')->get();

        $$module_name_singular = User::findOrFail($id);

        $body_class = 'profile-page';

        $userRoles = $$module_name_singular->roles->pluck('name')->all();
        $userPermissions = $$module_name_singular->permissions->pluck('name')->all();

        return view("frontend.$module_name.edit", compact('userRoles', 'userPermissions', 'module_name', "$module_name_singular", 'module_icon', 'module_action', 'title', 'roles', 'permissions', 'body_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $module_name = $this->module_name;
        $module_name_singular = Str::singular($this->module_name);

        if ($id != auth()->user()->id) {
            return redirect()->route('frontend.users.profile', $id);
        }

        $$module_name_singular = User::findOrFail($id);

        $$module_name_singular->update($request->except(['roles', 'permissions']));

        if ($id == 1) {
            $user->syncRoles(['administrator']);

            return redirect("admin/$module_name")->with('flash_success', 'Update successful!');
        }

        $roles = $request['roles'];
        $permissions = $request['permissions'];

        // Sync Roles
        if (isset($roles)) {
            $$module_name_singular->syncRoles($roles);
        } else {
            $roles = [];
            $$module_name_singular->syncRoles($roles);
        }

        // Sync Permissions
        if (isset($permissions)) {
            $$module_name_singular->syncPermissions($permissions);
        } else {
            $permissions = [];
            $$module_name_singular->syncPermissions($permissions);
        }

        return redirect("admin/$module_name")->with('flash_success', 'Update successful!');
    }

    /**
     * Remove the Social Account attached with a User.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function userProviderDestroy(Request $request)
    {
        $user_provider_id = $request->user_provider_id;
        $user_id = $request->user_id;

        if (!$user_provider_id > 0 || !$user_id > 0) {
            flash('Invalid Request. Please try again.')->error();

            return redirect()->back();
        } else {
            $user_provider = UserProvider::findOrFail($user_provider_id);

            if ($user_id == $user_provider->user->id) {
                $user_provider->delete();

                flash('<i class="fas fa-exclamation-triangle"></i> Unlinked from User, "' . $user_provider->user->name . '"!')->success();

                return redirect()->back();
            } else {
                flash('<i class="fas fa-exclamation-triangle"></i> Request rejected. Please contact the Administrator!')->warning();
            }
        }

        throw new GeneralException('There was a problem updating this user. Please try again.');
    }

    /**
     * Resend Email Confirmation Code to User.
     *
     * @param [type] $hashid [description]
     *
     * @return [type] [description]
     */
    public function emailConfirmationResend($id)
    {
        if ($id != auth()->user()->id) {
            if (auth()->user()->hasAnyRole(['administrator', 'super admin'])) {
                Log::info(auth()->user()->name . ' (' . auth()->user()->id . ') - User Requested for Email Verification.');
            } else {
                Log::warning(auth()->user()->name . ' (' . auth()->user()->id . ') - User trying to confirm another users email.');

                abort('404');
            }
        }

        $user = User::where('id', 'LIKE', $id)->first();

        if ($user) {
            if ($user->email_verified_at == null) {
                Log::info($user->name . ' (' . $user->id . ') - User Requested for Email Verification.');

                // Send Email To Registered User
                $user->sendEmailVerificationNotification();

                flash('Email Sent! Please Check Your Inbox.')->success()->important();

                return redirect()->back();
            } else {
                Log::info($user->name . ' (' . $user->id . ') - User Requested but Email already verified at.' . $user->email_verified_at);

                flash($user->name . ', You already confirmed your email address at ' . $user->email_verified_at->isoFormat('LL'))->success()->important();

                return redirect()->back();
            }
        }
    }

    public function labrereindex()
    {
        return view("expense::backend.labreres.index");
    }

    public function gettable(Request $request, $type = null)
    {
        if ($request->ajax()) {
            $expense = DB::select('SELECT * FROM labreres');

            $data = $expense;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';

                    return $btn;
                })
                ->addColumn('Name', function ($row) {
                    $typename = $row->firstname . " " . $row-> lastname;

                    return $typename;
                })
                ->addColumn('birthdate', function ($row) {
                    $typename = $row->birthdate ;

                    return $typename;
                })
                ->addColumn('sex', function ($row) {
                    $typename = $row->sex ;

                    return $typename;
                })
                ->addColumn('phone', function ($row) {
                    $typename = $row->phone ;

                    return $typename;
                })
                ->addColumn('address', function ($row) {
                    $typename = $row->address ;

                    return $typename;
                })
                ->addColumn('country', function ($row) {
                    $typename = $row->country ;

                    return $typename;
                })
                ->addColumn('nationality', function ($row) {
                    $typename = $row->nationality;

                    return $typename;
                })

                ->addColumn('action', function ($row) {
                    $user = Auth::user();
                    $roles = $user->getRoleNames();

                    foreach ($roles as $role) {
                        if ($role!="roomservice" || $role="super admin") {
                            $perm=$role;
                        } else {
                            $perm="NOPerm";
                        }
                    }
                    if ($perm=="super admin") {
                        $btn = '
                        <a href="#" data-id="'.$row->id.'" class="btn btn-success btn-sm  edit"title=" '. __('reservation.EditLaborere'). '"> <i class="fa fa-wrench"></i></a>
                        <a href="#" data-id="'.$row->id.'"
                        class="btn btn-danger btn-sm  delete" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i></a>


                        ';
                    } elseif ($perm=="roomservice") {
                        $btn ='
                            ';
                    } else {
                        $btn='';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function createlabrere()
    {
        $edit = false;
        return view("expense::backend.labreres.create", compact('edit'));
    }
    public function storelabrere(Request $request)
    {
        try {
            $laberer = new Labrere();
            $laberer->firstname = $request->firstname;
            $laberer->lastname = $request->lastname;
            $laberer->birthdate = $request->birthdate;
            $laberer->sex = $request->sex;
            $laberer->phone = $request->phone;
            $laberer->address = $request->address;
            $laberer->city = $request->city;
            $laberer->country = $request->country;
            $laberer->nationality = $request->nationality;
            $laberer->save();
        } catch (\Exception $e) {
            return $e->getMessage() . $e->getCode();
        }

        return view("expense::backend.labreres.index");
    }
    public function editlabrere($id)
    {
        $edit = true;
        $labrere = Labrere::find($id);
        return view("expense::backend.labreres.create", compact('edit', 'labrere'));
    }
    public function deletelabrere($id)
    {
        Labrere::where('id', $id)->delete();
        return view("expense::backend.labreres.index");
    }
    public function updatelabrere(Request $request, $id)
    {
        try {
            $laberer = Labrere::where('id', $id)->first();
            $laberer->firstname = $request->firstname;
            $laberer->lastname = $request->lastname;
            $laberer->birthdate = $request->birthdate;
            $laberer->sex = $request->sex;
            $laberer->phone = $request->phone;
            $laberer->address = $request->address;
            $laberer->city = $request->city;
            $laberer->country = $request->country;
            $laberer->nationality = $request->nationality;
            $laberer->save();
        } catch (\Exception $e) {
            return $e->getMessage() . $e->getCode();
        }
        return view("expense::backend.labreres.index");
    }
    public function RewardOrPunishment(Request $request, $type =null)
    {
        if ($request->ajax()) {
            if ($type=='p') {
                $expense = DB::select("  SELECT * FROM rewardorpunishment WHERE type ='p'  ");
            } elseif ($type=='r') {
                $expense = DB::select("  SELECT * FROM rewardorpunishment WHERE type ='r'  ");
            }

            $data = $expense;
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '';

                        return $btn;
                    })
                    ->addColumn('labrere', function ($row) {
                        $typename = $row->labrere;

                        return $typename;
                    })
                    ->addColumn('type', function ($row) {
                        $typename = $row->type ;

                        return $typename;
                    })
                    ->addColumn('why', function ($row) {
                        $typename = $row->why ;

                        return $typename;
                    })
                    ->addColumn('date', function ($row) {
                        $typename = $row->date ;

                        return $typename;
                    })

                    ->addColumn('action', function ($row) {
                        $user = Auth::user();
                        $roles = $user->getRoleNames();

                        foreach ($roles as $role) {
                            if ($role!="roomservice" || $role="super admin") {
                                $perm=$role;
                            } else {
                                $perm="NOPerm";
                            }
                        }
                        if ($perm=="super admin") {
                            $btn = '
                        <a href="#" data-id="'.$row->id.'"
                        class="btn btn-danger btn-sm  delete" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i></a>


                        ';
                        } elseif ($perm=="roomservice") {
                            $btn ='
                            ';
                        } else {
                            $btn='';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }
    public function showReward()
    {
        $type ='r';
        return view("expense::backend.labreres.showrorp", compact('type'));
    }
    public function showPunishment()
    {
        $type ='p';
        return view("expense::backend.labreres.showrorp", compact('type'));
    }
    public function createPunishment()
    {
        $type ='p';
        return view("expense::backend.labreres.createRorP", compact('type'));
    }
    public function createReward()
    {
        $type ='r';
        return view("expense::backend.labreres.createRorP", compact('type'));
    }
    public function storeReward(Request $request)
    {
        $labrere_s =  Labrere::where('id', $request->labrere)->first();
        $type = 'r';

        $re = new RewardOrPunishment();

        $re->customer_id = $request->input('labrere');

        $re->labrere = $labrere_s->firstname .' ' . $labrere_s->lastname;

        $re->price = $request->input('price');

        $re->date = $request->input('date');

        $re->type = $type;

        $re->why = $request->input('why');

        $re->save();
    }
    public function storePunishment(Request $request)
    {
        $labrere_s =  Labrere::where('id', $request->labrere)->first();
        $type = 'p';

        $re = new RewardOrPunishment();

        $re->customer_id = $request->input('labrere');

        $re->labrere = $labrere_s->firstname .' ' . $labrere_s->lastname;

        $re->price = $request->input('price');

        $re->date = $request->input('date');

        $re->type = $type;

        $re->why = $request->input('why');

        $re->save();
    }

    public function RewardorPunishmentDelete($id)
    {
        RewardOrPunishment::where('id', $id)->delete();
    }
}
