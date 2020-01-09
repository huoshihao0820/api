@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('pub_key') }}</div>

                    <div class="card-body">
                        <form action="pubkey_do" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <label class="form-check-label" for="remember">
                                            <textarea name="pubkey" id="" cols="30" rows="10"></textarea>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <input type="hidden" name="user_id" value="{{  Auth::user()->id }}">
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        保存
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


