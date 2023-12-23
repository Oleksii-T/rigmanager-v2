@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content_header')
    <x-admin.title
        text="Dashboard"
    />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #FF0000 1.91%, #ff5050 95.64%);">
                <div class="inner">
                    <h3>Users: {{$usersNumbers['total']}}</h3>
                    <p>Online: <b>{{$usersNumbers['online']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$usersNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$usersNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$usersNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$usersNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$usersNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$usersNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 47.5C5 42 9.5 37.5 15 37.5H25C30.5 37.5 35 42 35 47.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M26.2499 14.9999C29.7499 18.4999 29.7499 23.9999 26.2499 27.2499C22.7499 30.4999 17.2499 30.7499 13.9999 27.2499C10.7499 23.7499 10.4999 18.4999 13.7499 14.9999C16.9999 11.4999 22.7499 11.7499 26.2499 14.9999" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M40 35H47.5C51.75 35 55 38.25 55 42.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M48.25 16.75C50.75 19.25 50.75 23.25 48.25 25.5C45.75 27.75 41.75 28 39.5 25.5C37.25 23 37 19 39.5 16.75C41.75 14.5 45.75 14.5 48.25 16.75" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #0000FF 1.91%, #5050ff 95.64%);">
                <div class="inner">
                    <h3>Posts: {{$postsNumbers['total']}}</h3>
                    <p>Inactive: <b>{{$postsNumbers['inactive']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$postsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$postsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$postsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$postsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$postsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$postsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #00FF00 1.91%, #48ff48 95.64%);">
                <div class="inner">
                    <h3>Mailers: {{$mailersNumbers['total']}}</h3>
                    <p>Inactive: <b>{{$mailersNumbers['inactive']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$mailersNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$mailersNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$mailersNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$mailersNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$mailersNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$mailersNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #FFFF00 1.91%, #ffff43 95.64%);">
                <div class="inner" style="color:rgb(44, 44, 44)">
                    <h3>Imports: {{$importsNumbers['total']}}</h3>
                    <p>Success: <b>{{$importsNumbers['success']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$importsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$importsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$importsNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$importsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$importsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$importsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #800080 1.91%, #7c1a7c 95.64%);">
                <div class="inner">
                    <h3>Feedbacks: {{$feedbacksNumbers['total']}}</h3>
                    <p>From users: <b>{{$feedbacksNumbers['from-users']}}</b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$feedbacksNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$feedbacksNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$feedbacksNumbers['1w']}}</b>
                            <br>
                            Last 2w: <b>{{$feedbacksNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$feedbacksNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$feedbacksNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #FFA500 1.91%, #ffc760 95.64%);">
                <div class="inner">
                    <h3>Posts Views: {{$postViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$postViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$postViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$postViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$postViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$postViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$postViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #87CEEB 1.91%, #c4e7f5 95.64%);">
                <div class="inner">
                    <h3>Blog Views: {{$blogViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$blogViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$blogViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$blogViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$blogViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$blogViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$blogViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #FFC0CB 1.91%, #ffd6d6 95.64%);">
                <div class="inner">
                    <h3>User Views: {{$userViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$userViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$userViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$userViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$userViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$userViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$userViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #006400 1.91%, #258825 95.64%);">
                <div class="inner">
                    <h3>Notifications: {{$notificationViewsNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$notificationViewsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$notificationViewsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$notificationViewsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$notificationViewsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$notificationViewsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$notificationViewsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #D2691E 1.91%, #e59154 95.64%);">
                <div class="inner">
                    <h3>Messages: {{$messagesNumbers['total']}}</h3>
                    <p>-</p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$messagesNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$messagesNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$messagesNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$messagesNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$messagesNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$messagesNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box" style="background: linear-gradient(94.79deg, #007BA7 1.91%, #3199bf 95.64%);">
                <div class="inner">
                    <h3>Subscriptions: {{$subscriptionsNumbers['total']}}</h3>
                    <p>-<b></b></p>
                    <div class="row">
                        <p class="col-4">
                            Last 1d: <b>{{$subscriptionsNumbers['1d']}}</b>
                            <br>
                            Last 2d: <b>{{$subscriptionsNumbers['2d']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1w: <b>{{$subscriptionsNumbers['2w']}}</b>
                            <br>
                            Last 2w: <b>{{$subscriptionsNumbers['2w']}}</b>
                            <br>
                        </p>
                        <p class="col-4">
                            Last 1m: <b>{{$subscriptionsNumbers['1m']}}</b>
                            <br>
                            Last 2m: <b>{{$subscriptionsNumbers['2m']}}</b>
                            <br>
                        </p>
                    </div>
                </div>
                <div class="icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M48.535 15.32L42.1775 8.9625C41.24 8.0275 39.97 7.5 38.6425 7.5H22.5C19.7375 7.5 17.5 9.7375 17.5 12.5V39.285C17.5 42.0475 19.7375 44.285 22.5 44.285H45C47.7625 44.285 50 42.0475 50 39.285V18.8575C50 17.53 49.4725 16.26 48.535 15.32V15.32Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M50 20H40C38.62 20 37.5 18.88 37.5 17.5V7.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M42.5 44.285V47.5C42.5 50.2625 40.2625 52.5 37.5 52.5H15C12.2375 52.5 10 50.2625 10 47.5V20C10 17.2375 12.2375 15 15 15H17.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- models created chart --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Models Created</h3>
                    <div class="card-tools" style="width: 185px">
                        <div class="input-group">
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#alog-trivia">
                                Trivia
                            </a>
                            <input type="text" name="period" class="form-control daterangepicker-mult">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="models-created" data-url="{{route('admin.get-chart', 'models-created')}}"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Users per country chart --}}
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users per country</h3>
                </div>
                <div class="card-body">
                    <canvas id="user-per-country" data-url="{{route('admin.get-chart', 'users-per-country')}}"></canvas>
                </div>
            </div>
        </div>
        {{-- Posts per origin locale chart --}}
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Posts per origin locale</h3>
                </div>
                <div class="card-body">
                    <canvas id="posts-per-locale" data-url="{{route('admin.get-chart', 'posts-per-locale')}}"></canvas>
                </div>
            </div>
        </div>
        {{-- Notifications by group --}}
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notifications by group</h3>
                </div>
                <div class="card-body">
                    <canvas id="notifications-by-groups" data-url="{{route('admin.get-chart', 'notifications-by-groups')}}"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- Mailer emails chart --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mailer emails</h3>
                    <div class="card-tools" style="width: 185px">
                        <div class="input-group">
                            <input type="text" name="period" class="form-control daterangepicker-mult">
                </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                        </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart" id="revenue-chart">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="mailer-emails" data-url="{{route('admin.get-chart', 'mailer-emails')}}"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by posts count</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Posts</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usersByPostsCount as $user)
                                <tr>
                                    <th>
                                        {{$user->id}}:
                                        <a href="{{route("admin.users.show", $user)}}">
                                            {{$user->name}}
                                        </a>
                                        {{"<$user->email>"}}
                                    </th>
                                    <th>{{$user->posts_count}}</th>
                                    <th>{{$user->last_active_at?->diffForHumans() ?? '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by mailers count</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Mailers</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usersByMailersCount as $user)
                                <tr>
                                    <th>
                                        {{$user->id}}:
                                        <a href="{{route("admin.users.show", $user)}}">
                                            {{$user->name}}
                                        </a>
                                        {{"<$user->email>"}}
                                    </th>
                                    <th>{{$user->mailers_count}}</th>
                                    <th>{{$user->last_active_at?->diffForHumans() ?? '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by imports count</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Imports</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usersByImportsCount as $user)
                                <tr>
                                    <th>
                                        {{$user->id}}:
                                        <a href="{{route("admin.users.show", $user)}}">
                                            {{$user->name}}
                                        </a>
                                        {{"<$user->email>"}}
                                    </th>
                                    <th>{{$user->imports_count}}</th>
                                    <th>{{$user->last_active_at?->diffForHumans() ?? '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by post views count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Post Views</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usersByPostViewsCount as $user)
                                <tr>
                                    <th>
                                        {{$user->id}}:
                                        <a href="{{route("admin.users.show", $user->id)}}">
                                            {{$user->name}}
                                        </a>
                                        {{"<$user->email>"}}
                                    </th>
                                    <th>{{$user->total_views}}</th>
                                    <th>{{$user->last_active_at ? \Carbon\Carbon::parse($user->last_active_at)->diffForHumans() : '-'}}</th>
                                    <th>{{\Carbon\Carbon::parse($user->created_at)->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users been viewed</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Views</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usersByViewsCount as $user)
                                <tr>
                                    <th>
                                        {{$user->id}}:
                                        <a href="{{route("admin.users.show", $user->id)}}">
                                            {{$user->name}}
                                        </a>
                                        {{"<$user->email>"}}
                                    </th>
                                    <th>{{$user->views_count}}</th>
                                    <th>{{$user->last_active_at?->diffForHumans() ?? '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top users by contact requests count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Views</th>
                                <th>Last active at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usersByContactViewsCount as $user)
                                <tr>
                                    <th>
                                        {{$user->id}}:
                                        <a href="{{route("admin.users.show", $user)}}">
                                            {{$user->name}}
                                        </a>
                                        {{"<$user->email>"}}
                                    </th>
                                    <th>{{$user->total_views}}</th>
                                    <th>{{$user->last_active_at->diffForHumans()}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top posts by views count</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Views Count</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Is Active</th>
                                <th>Updated at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($postsByViewsCount as $post)
                                <tr>
                                    <th>
                                        {{$post->id}}:
                                        <a href="{{route('admin.posts.show', $post)}}">{{$post->title}}</a>
                                    </th>
                                    <th>
                                        {{$post->user->id}}:
                                        <a href="{{route("admin.users.show", $post->user)}}">
                                            {{$post->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$post->views_count}}</th>
                                    <th>{{$post->category->name}}</th>
                                    <th>{{$post->status}}</th>
                                    <th>{{$post->is_active ? '+' : '-'}}</th>
                                    <th>{{$user->updated_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top posts by price requests count</h3>
                </div>
                <div class="card-body">
                    <table id="posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Price Requests</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Is Active</th>
                                <th>Updated at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($postsByPriceRequestsCount as $post)
                                <tr>
                                    <th>
                                        {{$post->id}}:
                                        <a href="{{route('admin.posts.show', $post)}}">{{$post->title}}</a>
                                    </th>
                                    <th>
                                        {{$post->user->id}}:
                                        <a href="{{route("admin.users.show", $post->user)}}">
                                            {{$post->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$post->total_views}}</th>
                                    <th>{{$post->category->name}}</th>
                                    <th>{{$post->status}}</th>
                                    <th>{{$post->is_active ? '+' : '-'}}</th>
                                    <th>{{$user->updated_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top posts by oldest view</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Last View</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Is Active</th>
                                <th>Updated at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($postsByOldestView as $post)
                                <tr>
                                    <th>
                                        {{$post->id}}:
                                        <a href="{{route('admin.posts.show', $post)}}">{{$post->title}}</a>
                                    </th>
                                    <th>
                                        {{$post->user->id}}:
                                        <a href="{{route("admin.users.show", $post->user)}}">
                                            {{$post->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$post->last_activity_at}}</th>
                                    <th>{{$post->category->name}}</th>
                                    <th>{{$post->status}}</th>
                                    <th>{{$post->is_active ? '+' : '-'}}</th>
                                    <th>{{$user->updated_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top oldest posts without views</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Is Active</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($postsWithoutViews as $post)
                                <tr>
                                    <th>
                                        {{$post->id}}:
                                        <a href="{{route('admin.posts.show', $post)}}">{{$post->title}}</a>
                                    </th>
                                    <th>
                                        {{$post->user->id}}:
                                        <a href="{{route("admin.users.show", $post->user)}}">
                                            {{$post->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$post->category->name}}</th>
                                    <th>{{$post->status}}</th>
                                    <th>{{$post->is_active ? '+' : '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                    <th>{{$user->updated_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top categories by posts count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Posts count</th>
                                <th>Is Active</th>
                                <th>Created_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoriesByPostsCount as $category)
                                <tr>
                                    <th>
                                        {{$category->id}}
                                        <a href="{{route('admin.categories.show', $category)}}">
                                            {{$category->name}}
                                        </a>
                                    </th>
                                    <th>{{$category->parent->name??'-'}}</th>
                                    <th>{{$category->posts_count}}</th>
                                    <th>{{$category->is_active ? '+' : '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top categories by post views count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Total Post Views</th>
                                <th>Posts count</th>
                                <th>Is Active</th>
                                <th>Created_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoriesByPostsViews as $category)
                                <tr>
                                    <th>
                                        {{$category->id}}
                                        <a href="{{route('admin.categories.show', $category)}}">
                                            {{$category->name}}
                                        </a>
                                    </th>
                                    <th>{{$category->parent->name??'-'}}</th>
                                    <th>{{$category->total_views}}</th>
                                    <th>{{$category->posts()->count()}}</th>
                                    <th>{{$category->is_active ? '+' : '-'}}</th>
                                    <th>{{$user->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top mailers by emails send count</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Active</th>
                                <th>Emails Send</th>
                                <th>Posts Emailed</th>
                                <th>Last Mail At</th>
                                <th>Created_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mailersByEmails as $mailer)
                                <tr>
                                    <th>
                                        {{$mailer->id}}:
                                        <a href="{{route('admin.mailers.edit', $mailer)}}">
                                            {{$mailer->title}}
                                        </a>
                                    </th>
                                    <th>
                                        {{$mailer->user->id}}:
                                        <a href="{{route('admin.users.edit', $mailer->user)}}">
                                            {{$mailer->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$mailer->is_active ? '+' : '-'}}</th>
                                    <th>{{$mailer->total_emails}}</th>
                                    <th>
                                        @foreach ($mailer->posts as $p)
                                            <a href="{{route('admin.posts.edit', $p)}}">{{$p}}</a>,
                                        @endforeach
                                    </th>
                                    <th>{{$mailer->last_at->adminFormat()}}</th>
                                    <th>{{$mailer->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top mailers by oldest email</h3>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Active</th>
                                <th>Posts Emailed</th>
                                <th>Last Mail At</th>
                                <th>Created_at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mailersByOldestEmail as $mailer)
                                <tr>
                                    <th>
                                        {{$mailer->id}}:
                                        <a href="{{route('admin.mailers.edit', $mailer)}}">
                                            {{$mailer->title}}
                                        </a>
                                    </th>
                                    <th>
                                        {{$mailer->user->id}}:
                                        <a href="{{route('admin.users.edit', $mailer->user)}}">
                                            {{$mailer->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$mailer->is_active ? '+' : '-'}}</th>
                                    <th>
                                        @foreach ($mailer->posts as $p)
                                            <a href="{{route('admin.posts.edit', $p)}}">{{$p}}</a>,
                                        @endforeach
                                    </th>
                                    <th>{{$mailer->last_at->adminFormat()}}</th>
                                    <th>{{$mailer->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top oldest mailers without email</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Active</th>
                                <th>Updated at</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mailersWithoutEmail as $mailer)
                                <tr>
                                    <th>
                                        {{$mailer->id}}:
                                        <a href="{{route('admin.mailers.edit', $mailer)}}">
                                            {{$mailer->title}}
                                        </a>
                                    </th>
                                    <th>
                                        {{$mailer->user->id}}:
                                        <a href="{{route('admin.users.edit', $mailer->user)}}">
                                            {{$mailer->user->name}}
                                        </a>
                                    </th>
                                    <th>{{$mailer->is_active ? '+' : '-'}}</th>
                                    <th>{{$mailer->updated_at->adminFormat()}}</th>
                                    <th>{{$mailer->created_at->adminFormat()}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.activity-logs-triavia />
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/dashboard.js')}}"></script>
@endpush
