<?php


namespace App\GraphQL\Mutations;

use App\Models\Star;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

//use Nuwave\Lighthouse\Support\Traits\HandlesGlobalId;

class CreateStarMutation
{

    //use HandlesGlobalId;

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args)
    {
        // TODO implement the resolver
        Auth::loginUsingId(2);
        $user = Auth::user();
        logger(json_encode($user));

        if ($user->is_admin) {
            $userArgs  = $args['input']['userId'];

            return Star::create(['user_id'=> $userArgs]);
        }

    }
}
