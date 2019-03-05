<?php

namespace App\GraphQL\Mutations\Events;
use App\Models\Event;
use GraphQL\Error\UserError;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\Utils\GlobalId;

class Update
{
    /**
     * @param $root
     * @param array $args
     *
     * @return mixed
     * @throws \Throwable
     */

    public function resolve($root, array $args)
    {
        $input = $args['input'];

        $eventId =  GlobalId::decodeID($input['id']);

        $event = Event::find($eventId);

        throw_unless(
            Auth::user()->is_admin,
            UserError::class,
            'You do not have permission to modify a event'
        );

        $event->update([
            'date' => $input['date'],
            'name'=> $input['name'],
        ]);
        return $event;
    }
}
