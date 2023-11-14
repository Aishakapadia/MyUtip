<ol class="dd-list">
    @if($navigation_list->count())
    @foreach($navigation_list as $nav)
    <li class="dd-item dd3-item" data-id="{{ $nav->id }}" data-title="{{ $nav->title }}">
        <div class="dd-handle dd3-handle"> </div>
        <div class="dd3-content">
            {{ $nav->title }}
            <span style="float:right;">
                <a class="removeNav" data-nav-id="{{ $nav->id }}" title="Remove from navigation">Remove</a>
            </span>
        </div>
    </li>
    @endforeach
    @else
    <li class="">No record found.</li>
    @endif
</ol>
