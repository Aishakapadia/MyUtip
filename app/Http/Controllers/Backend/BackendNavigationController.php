<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Page;
use App\MenuLocation;
use App\MenuList;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\NavigationRequest;
use App\Navigation;
use Datatables;
use DB;

class BackendNavigationController extends BackendController
{
    public function getSettings()
    {
        $pages = Page::all();
        return view(admin_view('navigation.manage'))->with('pages', $pages);
    }

    public function getLoadModuleLinks()
    {
        $links = MenuList::getLinks();
        return view(admin_view('navigation.links'))->with('links', $links);
    }

    public function getLoadModuleLists()
    {
        $lists = MenuList::getLists();
        return view(admin_view('navigation.lists'))->with('lists', $lists);
    }

    public function getLoadModuleLocations(Request $request)
    {
        $menus = MenuLocation::all();
        $location_id = $request->location_id;
        return view(admin_view('navigation.locations'))->with('menus', $menus)->with('location_id', $location_id);
    }


    public function postPageList(Request $request)
    {
        $navId = $request->navId;
        $navigation = MenuList::where(['menu_id' => $navId]);

        return View(admin_view('navigation.navigation'))
            ->with('navigation_list', $navigation);
    }

    public function postAddList(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'title' => 'required|unique:menu_lists|max:120'
        ], [
            'title.required' => 'Label is required',
            'title.unique'   => 'Label has already been taken'
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors();
            $errors = json_decode($errors);
            return response()->json([
                'status' => false,
                'error'  => $errors
            ], 422);
        }

        $nav = new MenuList();
        $nav->parent = '0';
        $nav->title = $request->title;
        $nav->url = $request->url;
        if ($nav->save()) {
            return response()->json([
                'status'  => true,
                'message' => 'List has been added'
            ], 200);
        }
    }

    public function postHideList(Request $request)
    {
        $data = MenuList::find($request->id);
        $data->ready = 0;
        if ($data->save()) {
            return response()->json([
                'status'  => true,
                'message' => 'List has been hidden'
            ]);
        }
    }

    public function postRemoveList(Request $request)
    {
        $data = MenuList::find($request->id);
        if ($data->delete()) {
            return response()->json([
                'status'  => true,
                'message' => 'List has been removed'
            ]);
        }
    }

    public function postRemoveListFromLocation(Request $request)
    {
        $location_id = $request->location_id;
        $menu_list_id = $request->menu_list_id;

        $location = MenuLocation::find($location_id);
        $location->lists()->detach([$menu_list_id]);

        return response()->json([
            'status'  => true,
            'message' => 'List has been removed from location'
        ]);
    }

    public function postAddLinkToList(Request $request)
    {
        if ($request->ids) {
            foreach ($request->ids as $id) {
                $list = MenuList::find($id);
                $list->ready = 1;
                $list->save();
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Done'
        ]);
    }

    public function postAddPageToList(Request $request)
    {
        if ($request->ids) {
            foreach ($request->ids as $id) {
                $page = Page::find($id);

                $page_link = MenuList::where('page_slug', $page->slug)->get()->first();
                if ($page_link) {
                    // update and active
                    $list = MenuList::find($page_link->id);
                    $list->ready = 1;
                    $list->save();

                } else {
                    // insert and active
                    $list = new MenuList();
                    $list->title = $page->title;
                    $list->page_slug = $page->slug;
                    $list->ready = 1;
                    $list->save();
                }
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Done'
        ]);
    }

    public function postAddListToLocation(Request $request)
    {
        $location_id = $request->location_id;
        $location = MenuLocation::find($location_id);
        $location->lists()->attach($request->list_ids);

        return response()->json([
            'status'  => true,
            'message' => 'Done'
        ]);
    }


    public function postSaveNavByUrl(Request $request)
    {
        $navId = $request->navId;
        $navTitle = $request->navTitle;
        $navUrl = $request->navUrl;

        $nav = new MenuList();
//        $nav->parent_id = '0';
        $nav->menu_id = $navId;
        $nav->title = $navTitle;
        $nav->url = $navUrl;
        if ($nav->save()) {
            $navigation = MenuList::where('menu_id', $navId)->orderBy('sort', 'asc')->get();
            return View(admin_view('navigation.navigation'))
                ->with('navigation_list', $navigation)
                ->with('status', true);
        }

    }

    public function postRemoveNav(Request $request)
    {
        $navId = $request->navId;

        DB::table('menu_lists')
            ->where('id', $navId)
            ->delete();
    }

    public function postUpdateNavigationSorting(Request $request)
    {
        //dd($request->all());

        if ($request->all()) {

            // Reset Sort and Parent -> Child relations
            DB::table('menu_lists')->update(['sort' => 0, 'parent' => 0]);

            foreach ($request->all() as $items) {

                // 1st level
                if ($items) {
                    foreach ($items as $key_parent => $parent) {
                        //dump($parent);
                        DB::table('menu_lists')->where('id', $parent['id'])->update(['sort' => $key_parent]);

                        // 2nd level
                        if (isset($parent['children'])) {
                            foreach ($parent['children'] as $key_child => $child) {
                                DB::table('menu_lists')->where('id', $child['id'])->update(['sort' => $key_child, 'parent' => $parent['id']]);

                                // 3rd level
                                if (isset($child['children'])) {
                                    foreach ($child['children'] as $key_child_child => $child_child) {
                                        DB::table('menu_lists')->where('id', $child_child['id'])->update(['sort' => $key_child_child, 'parent' => $child['id']]);
                                    }
                                }
                            }
                        }
                    }
                }

            }

        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        $navigations = Navigation::all();
        $menus = Menu::all();

        return view(admin_view('navigation.create_edit'), compact('navigations', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(NavigationRequest $request)
    {
        $navigation = new Navigation();
        $navigation->title = $request->title;
        $navigation->page = $request->page;
        $navigation->order = $request->order;
        $navigation->parent_id = $request->parent_id;
        $navigation->menu_id = $request->menu_id;

        $navigation->save();
        return redirect()->back()->with('message', 'Record has been created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function getEdit($id)
    {
        $navigation = Navigation::find($id);
        $navigations = Navigation::all();
        $menus = Menu::all();

        return view(admin_view('navigation.create_edit'), compact('navigation', 'navigations', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postEdit(NavigationRequest $request, $id)
    {
        $navigation = Navigation::find($id);
        $navigation->title = $request->title;
        $navigation->page = $request->page;
        $navigation->order = $request->order;
        $navigation->parent_id = $request->parent_id;
        $navigation->menu_id = $request->menu_id;

        $navigation->save();
        return redirect()->back()->with('message', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */

    public function getDelete($id)
    {
        $navigation = Navigation::find($id);
        $navigation->delete();
        return redirect()->back()->with('message', 'Record has been deleted successfully.');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $navigation = Navigation::select(['id', 'page', 'title', 'order', 'created_at', 'updated_at']);

        return Datatables::of($navigation)
            ->addColumn('action', function ($navigation) {
                $urlEdit = admin_url('navigation/' . $navigation->id . '/edit');
                $urlDelete = admin_url('navigation/' . $navigation->id . '/delete');
                return '<a href="' . $urlEdit . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="' . $urlDelete . '" class="btn btn-xs btn-danger" onClick="return confirm(\'Are you sure, you want to delete this records?\');"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->make(true);
    }


    public function postSaveNavigation(Request $request)
    {
        $navId = $request->navId;
        $pageId = $request->pageId;
        $pageTitle = $request->pageTitle;
        $pageSlug = $request->pageSlug;

        $nav = new MenuList();
//        $nav->parent_id = '0';
        $nav->menu_id = $navId;
        $nav->title = $pageTitle;
        $nav->page_slug = $pageSlug; // slug of page
        if ($nav->save()) {
            $navigation = MenuList::where('menu_id', $navId)->orderBy('sort', 'asc')->get();
            return View(admin_view('navigation.navigation'))
                ->with('navigation_list', $navigation)
                ->with('status', true);
        }

    }

}
