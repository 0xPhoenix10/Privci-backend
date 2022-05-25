@extends('layouts.app')

@section('content')

<div class="container-fluid main-container pt-7 pb-7 bg-darker">
    <div class="text-white">
        <h1 class="text-white mb-2">Upload A Data Protection Policy</h1>
        <h4 class="text-white">This will allow users to ask questions and receive answers that are token directly from
            your data protection policy</h4>
        <p class="upload-note mb-5"><strong>Note:</strong> Content will only be available to colleagues within your
            organisation</p>
        <div class="d-flex align-items-center mb-4">
            <input class="col-md-8 mr-3 form-control" type="file" id="formFile" accept=".pdf, .doc, .docx, .txt">
            <input type="button" class="btn theme-background-color ml-2 mr-2" value="Upload and Extract Content"
                name="upload_content" onclick="analyze_uploaded_file()" id="analyze_file">
            <p class="upload-note">Supported:<br>pdf, doc, docx, text</p>
        </div>
        <div class="d-flex align-items-center">
            <h4 class="text-white mr-2">Or extract from a link: </h4>
            <input type="url" class="col-4 form-control" id="email" placeholder="Add link to extract..." name="url">
            <button class="btn theme-background-color ml-2 mr-2">Extract</button>
            <p class="upload-note">Supported:<br>pdf, html</p>
        </div>
    </div>

    <textarea name="policy_content" id="policy_content" rows="10" class="col-xl-12 mt-4 rounded mb-2"
        placeholder="Copy and paste policy text..."></textarea>

    <div class="d-flex align-items-center mb-3">
        <h4 class="mr-3 text-white">Add a title to this document: </h4>
        <input type="text" class="col form-control" id="policy_title" placeholder="Start typing..." name="policy_title">
    </div>
    <div class="d-flex align-items-center mb-5">
        <h5 class="mr-3 text-white">Adding a link to the original document will allow users to access it from the
            extension: </h5>
        <input type="text" class="col form-control" id="policy_link" placeholder="Paste link..." name="policy_link">
    </div>
    <div class="d-flex flex-row-reverse align-items-center mb-6">
        <button class="btn theme-background-color ml-3" id="save_btn">Save Upload</button>
        <p class="upload-note">Please make sure the extract has been properly parsed before saving</p>
    </div>
    @php
    if(!empty($policy)) {
    @endphp
    <div class="faq-accordian-container">
        <div class="faq-upload">
            <h2 class="text-white mr-3">Documents Uploaded</h2>
            <div class="col add-tab border-dark text-white p-2 faq-accordion bg-dark">Add a FAQ (up to 5 FAQ may be
                added)</div>
        </div>
        <div class="faq-panel bg-dark">
            <h4>A list of Frequently Asked Questions will be added to the extension and will be visible to colleagues
                within your organization</h4>
            <p>You may want to answer questioins regarding data DOs and DONTs, data sharing procedures, an the contact
                details of your Data Protection Officer.</p>
            <div class="d-flex align-items-center mb-1">
                <h3 class="col-2">Questions:</h3>
                <input type="text" class="col-8 form-control" placeholder="Maximum of 60 characters..."
                    id="doc_question" value="">
            </div>
            <div class="d-flex mb-2">
                <h3 class="col-2">Answer:</h3>
                <textarea name="" cols="" rows="5" class="col-8 form-control"
                    placeholder="Maximum of 1000 characters..." id="doc_answer"></textarea>
                <div class="col-2 d-flex align-items-end">
                    <button class="btn theme-background-color" id="save_faq_btn" onClick="onAddFaq()">Add</button>
                </div>
            </div>
        </div>
        <div class="mt-2 mb-3 pl-2" id="documents_uploaded">
            @foreach($policy as $item)
            <div class="d-flex align-items-center theme-color">
                <p class="mr-2 theme-color">{{$loop->index + 1}} - ({{$item->title}})</p>
                <i class="mr-1 fa fa-edit" style="cursor: pointer;" title="Edit policy"
                    onClick="onEditDoc({{$item->id}})"></i>
                <i class="fa fa-trash" style="cursor: pointer;" title="Delete policy"
                    onClick="onDeleteDoc({{$item->id}})"></i>
            </div>
            @endforeach
        </div>
    </div>
    @if(!empty($faq))
    <div>
        <div class="faq-upload">
            <h2>FAQs Added</h2>
        </div>
        <div class="mt-2 mb-3 pl-2">
            @foreach($faq as $item1)
            <div class="d-flex align-items-center theme-color">
                <p class="mr-2 theme-color">{{$loop->index + 1}} - ({{$item1->question}})</p>
                <i class="mr-1 fa fa-edit" title="Edit faq" style="cursor: pointer;"
                    onClick="onEditFaq({{$item1->id}})"></i>
                <i class="fa fa-trash" title="Delete faq" style="cursor: pointer;"
                    onClick="onDeleteFaq({{$item1->id}})"></i>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @php
    }
    @endphp
    <input type="hidden" id="policy_edit_number" value="">
    <input type="hidden" id="faq_edit_number" value="">
    <input type="hidden" id="faq_cnt" value="{{count($faq)}}">
</div>
@include('layouts.footers.auth')

<style>
.no-result-card {
    background-color: blue;
}
</style>



@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('argon') }}/js/policy.js"></script>
@endpush