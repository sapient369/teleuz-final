@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-lg-8">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form class="register" action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="dashboard-edit-profile__thumb mb-4">
                                    <div class="file-upload">
                                        <label class="edit-pen" for="update-photo"><i class="lar la-edit"></i></label>
                                        <input type="file" name="image" class="form-control form--control" id="update-photo" hidden="" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <img id="upload-img" src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile'), true) }}" alt="@lang('image')">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('First Name')</label>
                                            <input type="text" class="form-control form--control" name="firstname" value="{{ $user->firstname }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Last Name')</label>
                                            <input type="text" class="form-control form--control" name="lastname" value="{{ $user->lastname }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('E-mail Address')</label>
                                            <input class="form-control form--control" value="{{ $user->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile Number')</label>
                                            <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Address')</label>
                                            <input type="text" class="form-control form--control" name="address" value="{{ @$user->address }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('State')</label>
                                            <input type="text" class="form-control form--control" name="state" value="{{ @$user->state }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Zip Code')</label>
                                            <input type="text" class="form-control form--control" name="zip" value="{{ @$user->zip }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('City')</label>
                                            <input type="text" class="form-control form--control" name="city" value="{{ @$user->city }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Country')</label>
                                            <input class="form-control form--control" value="{{ @$user->country_name }}" disabled>
                                        </div>
                                    </div>
                                    @if($user->adult_password)
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Current Adult PIN')</label>
                                                <input type="password" class="form-control form--control" name="current_adult_pin" maxlength="4" placeholder="****">
                                            </div>
                                        </div>
                                    @endif                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Adult PIN')</label>
                                            <input type="password" class="form-control form--control" name="adult_pin" maxlength="4" placeholder="****">
                                        </div>
                                    </div>                                    
                                </div>
                                <button type="submit" class="cmn-btn w-100">@lang('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
