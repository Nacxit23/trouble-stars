<?php

namespace App\GraphQL\Mutations\Star;

use App\Models\Event;
use App\Models\Star;
use App\Models\User;
use GraphQL\Error\UserError;
use Nuwave\Lighthouse\Execution\Utils\GlobalId;

class Create
{
    /**
     * @param $root
     * @param array $args
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Throwable
     */
    public function resolve($root, array $args)
    {
        $input = $args['input'];
        $userId = GlobalId::decodeID($input['userId']);
        /** @var User $user */
        $user = User::find($userId);

        throw_unless($user, UserError::class, 'User not found');

        /** @var Star $star */
        $star = Star::create([
            'user_id' => $userId,
            'description' => $input['description'],
        ]);
        $starCount = $user->stars()
            ->where([
                'event_id' => null,
                'paid_at' => null,
            ])
            ->count();

        if ($starCount == 3) {
            $this->createEvent($user);

            $star->refresh();
        }

        return $star;
    }

    /**
     * @param User $user
     */
    protected function createEvent(User $user): void
    {
        /** @var Event $event */
        $event = Event::create([
            'name' => "Thanks {$user->name} 🤤",
        ]);

        Star::where('user_id', $user->id)
            ->where('event_id', null)
            ->where('paid_at', null)
            ->update([
                'event_id' => $event->id,
            ]);

        $event->users()->attach(User::all());
    }
}
