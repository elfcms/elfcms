@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="big-container">
        @if (Session::has('settingedited'))
            <div class="alert alert-success">{{ Session::get('settingedited') }}</div>
        @endif
        @if (Session::has('settingcreated  '))
            <div class="alert alert-success">{{ Session::get('settingcreated') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="errors-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="table-search-box">
            <form action="{{ route('admin.statistics.index') }}" method="get">
                <div class="input-box">
                    <label>
                        {{ __('elfcms::default.period') }}
                    </label>
                    <div class="input-wrapper">
                        <input type="date" name="from" id="from" value="{{ $from ?? $default_from }}"
                            placeholder="{{ __('elfcms::default.date_placeholder') }}">
                        <span class="input-period-separator"></span>
                        <input type="date" name="to" id="to" value="{{ $to ?? $default_to }}"
                            placeholder="{{ __('elfcms::default.date_placeholder') }}">
                    </div>
                    <div class="non-text-buttons">
                        <button type="submit" class="button search-button"></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="chart-box">
            <canvas id="visitchart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const ctx = document.getElementById('visitchart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chart['labels']) !!},
                    datasets: [
                        {
                        label: '{{ __('elfcms::default.unique_visits') }}',
                        data: {!! json_encode($chart['unique_visits']) !!},
                        borderColor: '#1e90ff',
                        backgroundColor: '#1e90ff',
                        borderWidth: 2,
                        cubicInterpolationMode: 'monotone'
                        },
                        {
                        label: '{{ __('elfcms::default.all_visits') }}',
                        data: {!! json_encode($chart['all_visits']) !!},
                        borderColor: '#ff3814',
                        backgroundColor: '#ff3814',
                        borderWidth: 2,
                        cubicInterpolationMode: 'monotone'
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <h2>{{ __('elfcms::default.unique_visits') }}</h2>
        <div class="widetable-wrapper">
            <table class="grid-table table-cols-9">
                <thead>
                    <tr>
                        <th>
                            {{ __('elfcms::default.created') }}
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'created_at', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['created_at' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            User ID
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'user_id', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['user_id' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Tmp user
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'tmp_user_uuid', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['tmp_user_uuid' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            IP
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'ip', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['ip' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            URI
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'uri', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['uri' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Referer
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'referer', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['referer' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Browser
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'browser', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['browser' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Mobile
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'mobile', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['mobile' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Method
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_u' => 'method', 'trend_u' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_u', ['method' => true])) {{ UrlParams::case('trend_u', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uniqueVisits as $visit)
                        <tr>
                            <td>
                                {{ $visit->created_at }}
                            </td>
                            <td>
                                @if (!empty($visit->user))
                                    <a href="{{ route('admin.user.users.edit', $visit->user_id) }}"
                                        title="{{ $visit->user->name() }}">{{ $visit->user_id }}</a>
                                @else
                                    {{ $visit->user_id }}
                                @endif
                            </td>
                            <td>
                                @if (!empty($visit->user))
                                    {{ $visit->user->tmp }}
                                @else
                                    {{ $visit->tmp_user_uuid }}
                                @endif
                            </td>
                            <td>{{ $visit->ip }}</td>
                            <td>
                                @if (!empty($visit->uri))
                                    <a href="{{ $visit->uri }}" target="_blank">{{ $visit->uri }}</a>
                                @else
                                    {{ $visit->uri }}
                                @endif
                            </td>
                            <td>{{ $visit->referer }}</td>
                            <td>{{ $visit->browser }}</td>
                            <td>{{ $visit->mobile }}</td>
                            <td>{{ $visit->method }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($uvPageCount > 1)
            <nav class="pagination-wrapper">

                <ul>
                    @if ($uvPage == 1)
                        <li class="disabled"><span class="pagination-backward"></span></li>
                    @else
                        <li><a href="{{ route('admin.statistics.index', UrlParams::addArr(['page_u' => $uvPage - 1])) }}"
                                class="pagination-backward"></a></li>
                    @endif
                    @for ($p = 1; $p <= $uvPageCount; $p++)
                        @if ($p == $uvPage)
                            <li class="active"><span>{{ $p }}</span></li>
                        @else
                            <li><a
                                    href="{{ route('admin.statistics.index', UrlParams::addArr(['page_u' => $p])) }}">{{ $p }}</a>
                            </li>
                        @endif
                    @endfor
                    @if ($uvPage < $uvPageCount)
                        <li><a href="{{ route('admin.statistics.index', UrlParams::addArr(['page_u' => $uvPage + 1])) }}"
                                class="pagination-forward"></a></li>
                    @else
                        <li class="disabled"><span class="pagination-forward"></span></li>
                    @endif
                </ul>

            </nav>
        @endif
        <h2>{{ __('elfcms::default.all_visits') }}</h2>
        <div class="widetable-wrapper">
            <table class="grid-table table-cols-9">
                <thead>
                    <tr>
                        <th>
                            {{ __('elfcms::default.created') }}
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'created_at', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['created_at' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            User ID
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'user_id', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['user_id' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Tmp user
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'tmp_user_uuid', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['tmp_user_uuid' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            IP
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'ip', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['ip' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            URI
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'uri', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['uri' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Referer
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'referer', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['referer' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Browser
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'browser', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['browser' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Mobile
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'mobile', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['mobile' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                        <th>
                            Method
                            <a href="{{ route('admin.statistics.index', UrlParams::addArr(['order_f' => 'method', 'trend_f' => ['desc', 'asc']])) }}"
                                class="ordering @if (UrlParams::case('order_f', ['method' => true])) {{ UrlParams::case('trend_f', ['desc' => 'desc'], 'asc') }} @endif"></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statistics as $visit)
                        <tr>
                            <td>
                                {{ $visit->created_at }}
                            </td>
                            <td>
                                @if (!empty($visit->user))
                                    <a href="{{ route('admin.user.users.edit', $visit->user_id) }}"
                                        title="{{ $visit->user->name() }}">{{ $visit->user_id }}</a>
                                @else
                                    {{ $visit->user_id }}
                                @endif
                            </td>
                            <td>
                                @if (!empty($visit->user))
                                    {{ $visit->user->tmp }}
                                @else
                                    {{ $visit->tmp_user_uuid }}
                                @endif
                            </td>
                            <td>{{ $visit->ip }}</td>
                            <td>
                                @if (!empty($visit->uri))
                                    <a href="{{ $visit->uri }}" target="_blank">{{ $visit->uri }}</a>
                                @else
                                    {{ $visit->uri }}
                                @endif
                            </td>
                            <td>{{ $visit->referer }}</td>
                            <td>{{ $visit->browser }}</td>
                            <td>{{ $visit->mobile }}</td>
                            <td>{{ $visit->method }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $statistics->links('elfcms::admin.layouts.pagination') }}

    </div>

@endsection
