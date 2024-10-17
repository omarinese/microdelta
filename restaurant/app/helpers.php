<?php
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;




if (! function_exists('create_default_user')) {
    function create_default_user()
    {
        $user = User::create([
            'name' => config('default_user.name', 'Treballador 1'),
            'email' => config('default_user.email','treballador1@gmail.com'),
            'password' => Hash::make(config('default_user.password','12345678'))
        ]);

        //El try per si tinc que crear 2 vegades el mateix usuari, si no petaria
        try {

            Team::create([
                'name' => $user->name . "'s Team",
                'user_id' => $user->id,
                'personal_team' => true,
            ]);
        } catch (\Exception $e) {
        }


    }
}
