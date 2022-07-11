@if (count($users) > 0)
    <ul class="list-unstyled">
        @foreach ($users as $user)
            <li class="media">
                {{--  ユーザーのメールアドレスをもとにGravatarを取得して表示  --}}
                <img src="{{ Gravatar::get($user->email, ['size' => 50]) }}" class="mr-2 rounded" alt="">
                <div class="media-body">
                    <div>
                        {{ $user->name }}
                    </div>
                    <div>
                        {{--  ユーザー詳細ページへのリンク　--}}
                        <p>{!! link_to_route('users.show', 'View profile', ['user' => $user->id]) !!}</p>
                    </div>
                </div>
            </li>
        @endforeach          
    </ul>
    {{--  ページネーションのリンク　--}}
    {{ $users->links() }}
@endif