<div class="portlet light bordered">

    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-link"></i>
            <span class="caption-subject font-purple sbold uppercase">2) Links</span>
        </div>
        <div class="actions">
            <div class="btn-group btn-group-devided" data-toggle="buttons">
                <label class="btn btn-transparent green btn-circle btn-sm" id="add_link_to_list">
                    ADD TO LIST
                </label>
            </div>
        </div>
    </div>

    <div class="portlet-body form">

        <form action="" role="form" class="form-horizontal">
            <div class="form-body">

                <div class="form-group hide">
                    <div class="col-md-12">
                        <span id="custom_links_msgs">
                            <div class="alert alert-success alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert"
                               aria-label="close">&times;</a>
                                <strong>Success!</strong> Indicates a successful or positive action.
                            </div>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Label <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Label">
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Url</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="url" id="url" placeholder="Url">
                        <span class="help-block">Leave url empty, if you want to create non clickable link or a group.</span>
                    </div>
                    <div class="col-md-2">
                        <label class="btn btn-transparent blue btn-circle btn-sm"
                               id="add_list">
                            Save
                        </label>
                    </div>
                </div>

                <div id="menu_lists">
                    <table class="table table-hover table-condensed kjq_parent_child">
                        <tr>
                            <th width="50px">
                                {!! myCheckbox('parent_links', 'check-all-links kjq_parent', null, true) !!}
                            </th>
                            <th>Label</th>
                            <th>Url</th>
                            <th class="text-right">Action</th>
                        </tr>
                        @if($links->count())
                            @foreach($links as $link)
                                <tr>
                                    <td>
                                        {!! myCheckbox('link_'.$link->id, 'links kjq_child', null, false, ['data-title' => $link->title, 'data-slug' => $link->slug, 'data-id' => $link->id]) !!}
                                        {{--<input type="checkbox" class="links"--}}
                                               {{--id="{{ $link->id }}"--}}
                                               {{--data-title="{{ $link->title }}"--}}
                                               {{--data-slug="{{ $link->slug }}"--}}
                                        {{-->--}}
                                    </td>
                                    <td>{{ $link->title }}</td>
                                    <td>{{ $link->url }}</td>
                                    <td class="text-right">
                                        <a href="javascript:;" class="remove-list"
                                           data-singleton="true" data-toggle="confirmation" data-placement="left"
                                           data-id="{{ $link->id }}">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            Remove
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No record found</td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </form>
    </div>
</div>