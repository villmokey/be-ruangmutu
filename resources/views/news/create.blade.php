@component('layouts.app')
    @section('title','Tambah Berita & Artikel')
@section('ContentMenu','Tambah Berita & Artikel')

@section('subHeader')
    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <a href="{{route('news.index')}}" class="btn btn-label-primary btn-bold btn-sm btn-icon-h kt-margin-l-10">
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Tambah Berita & Artikel
                            </h3>
                        </div>
                    </div>

                    <form class="kt-form kt-form--label-right" onsubmit="createNews(this,event)" method="POST">
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Judul Artikel (ID) *</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" required placeholder="" id="title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Judul Artikel (EN) *</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" required placeholder="" id="title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Judul Artikel (Norsk) *</label>
                                <div class="col-6">
                                    <input class="form-control" type="text" required placeholder="" id="title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Deskripsi (ID) *</label>
                                <div class="col-6">
                                    <textarea name="" id="" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Deskripsi (EN) *</label>
                                <div class="col-6">
                                    <textarea name="" id="" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-2 col-form-label">Deskripsi (Norsk) *</label>
                                <div class="col-6">
                                    <textarea name="" id="" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="name" class="col-2 col-form-label">Gambar</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="kt-avatar kt-avatar--outline" id="kt_avatar">
                                        <div class="kt-avatar__holder" style="background-image: url({{asset('assets/media/users/man.png')}})"></div>
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                            <em class="fa fa-pen"></em>
                                            <input type="file" id="file" name="profile_avatar" accept=".png, .jpg, .jpeg">
                                        </label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                                <em class="fa fa-times"></em>
                                            </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-6">
                                    </div>
                                    <div class="col-lg-6 kt-align-right">
                                        <a href="{{route('news.index')}}" class="btn btn-label-primary">
                                            Kembali
                                        </a>
                                        <button type="submit" id="saveBtn" class="btn btn-primary">SIMPAN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
{{--    <script src="{{asset('app/build/news.js')}}" type="text/javascript"></script>--}}
@endsection
@endcomponent
