<div class="portlet light bordered">

    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-bars"></i>
            <span class="caption-subject font-purple sbold uppercase">3) List</span>
        </div>
        <div class="actions">
            <div class="btn-group btn-group-devided" data-toggle="buttons">
                <label class="btn btn-transparent green btn-circle btn-sm" id="add_list_to_location">
                    ADD TO LOCATION
                </label>
            </div>
        </div>
    </div>

    <div class="portlet-body form">
        <table class="table table-hover table-condensed kjq_parent_child" id="table_lists">
            <tr>
                <th width="50px">
                    {!! myCheckbox('parent-list', 'check-all-lists kjq_parent', null, true) !!}
                </th>
                <th>Label</th>
                <th>Page</th>
                <th>Url</th>
                <th class="text-right">Action</th>
            </tr>
            @if($lists->count())
                @foreach($lists as $list)
                    <tr>
                        <td>
                            {!! myCheckbox($list->id, 'lists kjq_child', null, false, ['data-title' => $list->title, 'data-url' => $list->url, 'data-slug' => $list->slug, 'data-id' => $list->id]) !!}
                        </td>
                        <td>{{ $list->title }}</td>
                        <td>{{ $list->page_slug }}</td>
                        <td>{{ $list->url }}</td>
                        <td class="text-right">
                            <a href="javascript:;" class="hide-list"
                               data-id="{{ $list->id }}"
                               data-singleton="true" data-toggle="confirmation" data-placement="left"
                            >
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Remove
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">No record found</td>
                </tr>
            @endif
        </table>
    </div>
</div>