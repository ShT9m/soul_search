@extends('layouts.app')

@section('styles')
    <link href="{{ mix('css/message.css') }}" rel="stylesheet">
@endsection

@section('title', 'Messages')

@section('content')
<div class="d-flex justify-content-center p-0">
    <!-- Users -->
    <div class="col-2 bg-white tag-bar border">
        <ul class="nav nav-pills flex-column px-0">
            @foreach($all_users as $a_user)
                @php
                    $message_to = $a_user->messageTo(Auth::id());
                    $message_from = $a_user->messageFrom(Auth::id());
                @endphp
                @if($message_to || $message_from || $a_user->followedBy(Auth::id()))
                    <li class="nav-item my-2">
                        <div class="row">
                            <div class="col-auto">
                                @if ($a_user->avatar)
                                    <img src="{{ asset('/storage/avatars/'. $a_user->avatar) }}" class="avatar-sm rounded-circle" alt="">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </div>
                            <div class="col">
                                <a href="{{ route('messages.show', ['user' => $a_user->id]) }}" class="flex-fill nav-link">
                                    <div class="text-dark">
                                        {{$a_user->username}}
                                    </div>
                                    <div class="text-dark">
                                        {{-- show the latest message --}}
                                        <span class="text-muted">
                                            @if($message_to && $message_from)
                                                @if($message_to->pivot->created_at > $message_from->pivot->created_at)
                                                    @if($message_to->pivot->text)
                                                        {{ $message_to->pivot->text }}
                                                    @else
                                                        {{ $a_user->username }} sent an image.
                                                    @endif
                                                @else
                                                    @if($message_from->pivot->text)
                                                        You: {{ $message_from->pivot->text }}
                                                    @else
                                                        You sent an image.
                                                    @endif
                                                @endif
                                            @elseif($message_to)
                                                @if($message_to->pivot->text)
                                                    {{ $message_to->pivot->text }}
                                                @else
                                                    {{ $a_user->username }} sent an image.
                                                @endif
                                            @else
                                                @if($message_from->pivot->text)
                                                    You: {{ $message_from->pivot->text }}
                                                @else
                                                    You sent an image.
                                                @endif
                                            @endif
                                        </span>

                                    </div>
                                </a>
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>

    <!-- Messages -->
    <div class="col position-relative" style="height: 95%">
        @if($user->id !== Auth::id())
            <!-- Head -->
            @include('users.messages.contents.head')

            <!-- Body -->
            <div class="row mt-2 p-0 chat-body">
                @include('users.messages.contents.body')
            </div>
        @endif

    </div>
</div>
@endsection
