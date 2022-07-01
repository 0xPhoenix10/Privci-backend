<div class="p-2 medium-card rounded text-light">
    <div class="row theme-color">
        <div class="col-xl-5 d-flex">
            <h4 class="m-0 mr-2 theme-color">Name:</h4>
            <p class="m-0" title="{{$domain_detail->company_name}}"
                style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{$domain_detail->company_name}}</p>
        </div>
        <div class="col-xl-6 d-flex">
            @if($domain_detail->no_of_breaches > 0)
            <h4 class="m-0 mr-2 theme-color">No of breach(es):</h4>
            <p class="m-0">{{$domain_detail->no_of_breaches}}</p>
            @endif
        </div>
        <div class="col-xl-1 text-right">
            <p class="page-show">
                {{$domain_detail->no_of_breaches > 1 ? '1 of ' . $domain_detail->no_of_breaches : ''}}
            </p>
        </div>
    </div>
    @php
    $breach_info = json_decode($domain_detail->breach_info);
    $array = get_object_vars($breach_info);
    if(empty($array)) {
    @endphp
    <div class="no-breach">
        No breach infos!
    </div>
    @php
    } else {
    $breach_count = count($array);
    $first_breach_array = get_object_vars($array[0]);
    $length1 = strlen('You can read more at ');
    $length2 = strlen($domain_detail->monitoring_domain);
    $length = 180 - $length1 - $length2;
    $breach_summary = substr($first_breach_array['breach summary'], 0, $length);
    $breach_summary .= '...';
    @endphp
    <div class="breach-content">
        <div class="search-result-info">
            <h4 class="m-0 text-light">Breach Date:</h4>
            <p class="col m-0 breach-date">{{$first_breach_array['breach date']}}</p>
        </div>
        <div class="search-result-info">
            <h4 class="m-0 text-light">No of Records:</h4>
            <p class="col m-0 breach-no-of-records">{{$first_breach_array['no of records']}}</p>
        </div>
        <div class="search-result-info">
            <h4 class="m-0 p-0 text-light">Summary:</h4>
            <p class="col-11 m-0 breach-summary-hidden">
                {{$first_breach_array['breach summary']}}
                <a href="{{$first_breach_array['reference']}}" target="_blank"
                    class="breach-reference col m-0 p-0 breach-refer">
                    You can read more at {{$domain_detail->monitoring_domain}}
                </a>
            </p>
            <p class="col-11 m-0 breach-summary">
                {{$breach_summary}}
                @if(strlen($first_breach_array['breach summary']) > $length)
                <a href="#" class="read-more">read more</a>
                @else
                <a href="{{$first_breach_array['reference']}}" target="_blank"
                    class="breach-reference col m-0 p-0 breach-refer">
                    You can read more at {{$domain_detail->monitoring_domain}}
                </a>
                @endif
            </p>
        </div>
        <!-- <div class="search-result-info">
            <h4 class="m-0 text-light">Reference:</h4>
            <a href="{{$first_breach_array['reference']}}" target="_blank"
                class="breach-reference col m-0 breach-refer">
                {{$first_breach_array['reference']}}
            </a>
        </div> -->
    </div>

    <div class="d-flex justify-content-end">
        <p class="breach-previous m-0 p-0 theme-color pagination-button">Previous</p>
        <ul class="d-flex align-items-center ml-2 m-0 mr-2 p-0 pagination-button">
            @php
            for($i=0; $i<$breach_count; $i++) { @endphp <li
                class="breach-pagination-btn list-unstyled pl-2 pr-2 {{$i==0 ? 'page-selected' : ''}}"
                data-index="{{$i}}">
                {{$i+1}}</li>
                @php
                }
                @endphp
        </ul>
        <p class="breach-next m-0 p-0 theme-color pagination-button">Next</p>
    </div>
    @php
    }
    @endphp
</div>
<div class="accordian-container mb-5 mt-2">
    <div class="d-flex">
        <h4 class="m-0 mr-2 text-light policy-link" title="Click to go to the privacy policy" style="cursor: pointer">
            What is the
            privacy policy saying?
        </h4>
        <p class="privacy-policy m-0" title="{{$domain_detail->privacy_policy}}"
            style="width: 55%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            @php
            if(!str_contains($domain_detail->privacy_policy, 'http')):
            @endphp
            <a href="#" class="text-warning pol-link no-link">[{{$domain_detail->privacy_policy}}]</a>
            @php
            else:
            @endphp
            <a href="{{$domain_detail->privacy_policy}}" class="pol-link"
                target="_blank">{{$domain_detail->privacy_policy}}</a>
            @endif
        </p>
    </div>
    <button class="accordion">🛒 Is the website selling or renting the personal information of its users?
        @php
        if($domain_detail->data_sell != ""):
        @endphp
        <i class="fa-solid fa-caret-right icon-exist"></i>
        @php
        else:
        @endphp
        <i class="fa-solid fa-triangle-exclamation icon-empty"></i>
        @php
        endif;
        @endphp
    </button>
    <div class="panel">
        @php
        if($domain_detail->data_sell != "") {
        $str = substr($domain_detail->data_sell, 3);
        $string = '...<br>';
        $string .= $str;
        $string .= '<br /> ...';
        $string = str_replace('...,', '...', $string);
        $string = str_replace('<br>,', '<br>', $string);
        echo $string;
        }
        @endphp
    </div>
    <button class="accordion">👥 Does the website share personal information for marketing purposes?
        @php
        if($domain_detail->data_share != ""):
        @endphp
        <i class="fa-solid fa-caret-right icon-exist"></i>
        @php
        else:
        @endphp
        <i class="fa-solid fa-triangle-exclamation icon-empty"></i>
        @php
        endif;
        @endphp
    </button>
    <div class="panel">
        @php
        if($domain_detail->data_share != "") {
        $str = substr($domain_detail->data_share, 4);
        $str = substr($str, 0, -7);
        $string = '...';
        $string .= $str;
        $string .= '<br /> ...';
        $string = str_replace('...,', '...', $string);
        $string = str_replace('<br>,', '<br>', $string);
        echo $string;
        }
        @endphp
    </div>
    <button class="accordion">📨 Are users able to opt-out of data sale, marketing, or advertising?
        @php
        if($domain_detail->opt_out != ""):
        @endphp
        <i class="fa-solid fa-caret-right icon-exist"></i>
        @php
        else:
        @endphp
        <i class="fa-solid fa-triangle-exclamation icon-empty"></i>
        @php
        endif;
        @endphp
    </button>
    <div class="panel">
        @php
        if($domain_detail->opt_out != "") {
        $str = substr($domain_detail->opt_out, 4);
        $str = substr($str, 0, -7);
        $string = '...';
        $string .= $str;
        $string .= '<br /> ...';
        $string = str_replace('...,', '...', $string);
        $string = str_replace('<br>,', '<br>', $string);
        echo $string;
        }
        @endphp
    </div>
</div>

<div class="p-0 mb-2 d-flex justify-content-between email-header">
    <div class="col-9 p-0 d-flex m-0 align-items-center">
        <h4 class="text-light mr-1" style="font-size: 14px">Users who may have submitted data, such as work email, to
        </h4>
        <a class="theme-color" href="https://{{$domain_detail->monitoring_domain}}" target="_blank">
            {{$domain_detail->monitoring_domain}}
        </a>
    </div>
    <div class="form-check d-flex align-items-center">
        <label class="form-check-label text-light" for="select-all" style="font-size: 15px"> Select all on this page
        </label>
        <input class="form-check-input" type="checkbox" name="" id="select-all" />
    </div>
</div>
<form action="/searchemail" method="GET" id="search-email-form">
    <div class="pl-3 pt-2 medium-card rounded text-light email-pane">
        @php
        $total_email_count = count($domain_emails) > 30 ? 30 : count($domain_emails);
        $page_length = count($domain_emails) > 30 ? ceil(count($domain_emails) / 30) : 1;

        if($total_email_count == 0) $page_length = 0;

        if($total_email_count == 0) {
        echo "<div class='no-emails'>No Emails!</div>";
        } else {
        echo "<div class='row email-list'>";
            for($i=0; $i < $total_email_count; $i++) { if(trim($domain_emails[$i]) !='' ) { @endphp <div
                class="col-lg-4 col-md-6">
                <div class="form-check">
                    <label class="form-check-label ellipsis" for="">
                        <input type="checkbox" class="form-check-input" name="sel_email[]"
                            value="{{$domain_emails[$i]}}">{{$domain_emails[$i]}}
                    </label>
                </div>
        </div>
        @php
        }}
        echo "
    </div>";}
    @endphp

    <div class="empty-email-pane"></div>
    <div class="row p-0 m-0 mt-3 justify-content-between search-footer">
        @php
        if($page_length > 0) {
        @endphp
        <div><span class="theme-color start"> 1</span>-<span class="theme-color end">
                {{$page_length > 1 ? 30 : $total_email_count-1}}</span> of <span
                class="theme-color">{{count($domain_emails)-1}}</span> records</div>
        @php
        if($page_length > 1) {
        @endphp
        <div class="col-2 m-0 mr-3 p-0 d-flex justify-content-between">
            <span class="theme-color email-pagination-button previous disabled" data-index="0">Previous</span>
            <span class="theme-color email-pagination-button next" data-index="1">Next</span>
        </div>
        @php
        }
        }
        @endphp
    </div>
    <input type="hidden" value="multi" name="multi_search">
</form>