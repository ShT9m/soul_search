<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Tag;
use App\Models\UserTag;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    use RegistersUsers;

    private $start_tag;

    public function __construct(Tag $start_tag)
    {
        $this->start_tag = $start_tag;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'tag_name' => ['required', 'array'],
            'tag_name.*' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:255',]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    public function index(){
        return view('auth.register');
    }

    protected function create(array $data)
    {
        $user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();
        $user_id = $user->id;
        $db_tags = Tag::get();

        foreach ($data['tag_name'] as $tag)
        {
            $tagFound = false;

            foreach ($db_tags as $db_tag)
            {
                if ($tag == $db_tag->name)
                {
                    $tagFound = true;
                    UserTag::create(['tag_id' => $db_tag->id, 'user_id' => $user_id, 'tag_category' => 'main']);

                    break;
                }
            }

            if (!$tagFound) //$tagFound == false
            {
                $tag = Tag::create(['name'=>$tag]);
                UserTag::create(['tag_id' => $tag->id, 'user_id' => $user_id, 'tag_category' => 'main']);
            }
        }

        return $user;
    }
}
