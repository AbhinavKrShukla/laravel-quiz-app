<div class="wrapper">
    <div class="container">
        <div class="row">

            <div class="span3">
                <div class="sidebar">
                    <ul class="widget widget-menu unstyled">
                        <li class="active"><a href="{{url('/')}}"><i class="menu-icon icon-dashboard"></i>Dashboard
                            </a></li>
                        <li><a href="{{route('quiz.create')}}"><i class="menu-icon icon-bullhorn"></i>Create Quiz</a>
                        </li>
                        <li><a href="{{route('quiz.index')}}"><i class="menu-icon icon-inbox"></i>View All Quiz <b class="label green pull-right">
                                    {{\App\Models\Quiz::all()->count()}}</b> </a></li>
                    </ul>
                    <!--/.widget-nav-->

                    <ul class="widget widget-menu unstyled">
                        <li class="active"><a href="{{route('question.create')}}"><i class="menu-icon icon-dashboard"></i>Create Question
                            </a></li>
                        <li><a href="{{route('question.index')}}"><i class="menu-icon icon-inbox"></i>View Questions <b class="label green pull-right">
                                    {{\App\Models\Question::all()->count()}}</b> </a></li>
                    </ul>
                    <!--/.widget-nav-->

                    <ul class="widget widget-menu unstyled">
                        <li class="active"><a href="{{route('user.create')}}"><i class="menu-icon icon-dashboard"></i>Create User
                            </a></li>
                        <li><a href="{{route('user.index')}}"><i class="menu-icon icon-inbox"></i>View Users<b class="label green pull-right">
                                    {{\App\Models\User::all()->count()}}</b> </a></li>
                    </ul>
                    <!--/.widget-nav-->

                    <ul class="widget widget-menu unstyled">
                        <li class="active"><a href="{{route('assign.exam')}}"><i class="menu-icon icon-dashboard"></i>Assign Exam
                            </a></li>
                        <li><a href="{{route('view.exam')}}"><i class="menu-icon icon-inbox"></i>View User Exam<b class="label green pull-right">
                                    {{DB::table('quiz_user')->count()}}</b> </a></li>
                    </ul>
                    <!--/.widget-nav-->

                    <ul class="widget widget-menu unstyled">
                        <li class="active"><a href="{{url('result')}}"><i class="menu-icon icon-dashboard"></i>View Result
                            </a></li>
                    </ul>
                    <!--/.widget-nav-->

                    <ul class="widget widget-menu unstyled">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="menu-icon icon-signout"></i>{{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
                <!--/.sidebar-->
            </div>
