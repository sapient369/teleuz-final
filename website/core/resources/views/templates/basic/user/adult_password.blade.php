@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="section--bg ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form method="post" action="{{ route('user.adult.unlock') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">@lang('Enter Adult PIN')</label>
                                    <input type="password" name="pin" class="form-control form--control" maxlength="4" required>
                                </div>
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </form>
                            @if(isset($showSubscribe) && $showSubscribe && isset($category))
                                <div class="mt-3">
                                    <form method="POST" action="{{ route('user.subscribe.channel', $category->id) }}">
                                        @csrf
                                        <button class="btn btn--base w-100" type="submit">
                                            @lang('Subscribe to :name', ['name' => __($category->name)])
                                        </button>
                                    </form>
                                </div>
                            @endif                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection