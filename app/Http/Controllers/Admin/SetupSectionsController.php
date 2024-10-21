<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Blog;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class SetupSectionsController extends Controller
{
    protected $languages;

    public function __construct()
    {
        $this->languages = Language::whereNot('code', LanguageConst::NOT_REMOVABLE)->get();
    }

    /**
     * Register Sections with their slug
     * @param string $slug
     * @param string $type
     * @return string
     */
    public function section($slug, $type)
    {
        $sections = [
            'recharge'    => [
                'view'      => "rechargeView",
                'update'    => "rechargeUpdate",
            ],
            'footer'    => [
                'view'      => "footerView",
                'update'    => "footerUpdate",
                'itemStore'     => "footerItemStore",
                'itemUpdate'    => "footerItemUpdate",
                'itemDelete'    => "footerItemDelete",
            ],
            'testimonial'  => [
                'view'      => "testimonialView",
                'update'    => "testimonialUpdate",
                'itemStore'     => "testimonialItemStore",
                'itemUpdate'    => "testimonialItemUpdate",
                'itemDelete'    => "testimonialItemDelete",
            ],
            'contact'    => [
                'view'      => "contactView",
                'update'    => "contactUpdate",
            ],
            'user-login'    => [
                'view'      => "userLoginView",
                'update'    => "userLoginUpdate",
            ],
            'user-register'    => [
                'view'      => "userRegisterView",
                'update'    => "userRegisterUpdate",
            ],
            'solutions'  => [
                'view'      => "solutionView",
                'update'    => "solutionUpdate",
                'itemStore'     => "solutionItemStore",
                'itemUpdate'    => "solutionItemUpdate",
                'itemDelete'    => "solutionItemDelete",
            ],
            'why-choose'  => [
                'view'      => "whyChooseView",
                'update'    => "whyChooseUpdate",
                'itemStore'     => "whyChooseItemStore",
                'itemUpdate'    => "whyChooseItemUpdate",
                'itemDelete'    => "whyChooseItemDelete",
            ],
            'about'  => [
                'view'      => "aboutView",
                'update'    => "aboutUpdate",
                'itemStore'     => "aboutItemStore",
                'itemUpdate'    => "aboutItemUpdate",
                'itemDelete'    => "aboutItemDelete",
            ],
            'donetion'  => [
                'view'      => "donetionView",
                'update'    => "donetionUpdate",
                'itemStore'     => "donetionItemStore",
                'itemUpdate'    => "donetionItemUpdate",
                'itemDelete'    => "donetionItemDelete",
            ],
            'services'  => [
                'view'      => "servicesView",
                'update'    => "servicesUpdate",
                'itemStore'     => "servicesItemStore",
                'itemUpdate'    => "servicesItemUpdate",
                'itemDelete'    => "servicesItemDelete",
            ],
            'header-floting'  => [
                'view'      => "headerFlotingView",
                'update'    => "headerFlotingUpdate",
                'itemStore'     => "headerFlotingItemStore",
                'itemUpdate'    => "headerFlotingItemUpdate",
                'itemDelete'    => "headerFlotingItemDelete",
            ],
            'header-slider'  => [
                'view'      => "headerSliderView",
                'update'    => "headerSliderUpdate",
                'itemStore'     => "headerSliderItemStore",
                'itemUpdate'    => "headerSliderItemUpdate",
                'itemDelete'    => "headerSliderItemDelete",
            ],
            'faq'  => [
                'view'      => "faqView",
                'update'    => "faqUpdate",
                'itemStore'     => "faqItemStore",
                'itemUpdate'    => "faqItemUpdate",
                'itemDelete'    => "faqItemDelete",
            ],
            'monitoring'  => [
                'view'      => "monitoringView",
                'update'    => "monitoringUpdate",
            ],
            'best-item'  => [
                'view'      => "bestItemView",
                'update'    => "bestItemUpdate",
            ],
            'latest-item'  => [
                'view'      => "latestItemView",
                'update'    => "latestItemUpdate",
            ],
            'glance'  => [
                'view'          => "glanceView",
                'update'        => "glanceUpdate",
                'itemUpdate'    => "glanceItemUpdate",
            ],
            'intro'  => [
                'view'          => "introView",
                'update'        => "introUpdate",
            ],
            'category'    => [
                'view'      => "categoryView",
            ],
            'blog-section'    => [
                'view'      => "blogView",
                'update'    => "blogUpdate",
            ],
        ];

        if (!array_key_exists($slug, $sections)) abort(404);
        if (!isset($sections[$slug][$type])) abort(404);
        $next_step = $sections[$slug][$type];
        return $next_step;
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function sectionView($slug)
    {
        $section = $this->section($slug, 'view');
        return $this->$section($slug);
    }

    /**
     * Method for distribute store method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemStore(Request $request, $slug)
    {
        $section = $this->section($slug, 'itemStore');
        return $this->$section($request, $slug);
    }

    /**
     * Method for distribute update method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemUpdate(Request $request, $slug)
    {
        $section = $this->section($slug, 'itemUpdate');
        return $this->$section($request, $slug);
    }

    /**
     * Method for distribute delete method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionItemDelete(Request $request, $slug)
    {
        $section = $this->section($slug, 'itemDelete');
        return $this->$section($request, $slug);
    }

    /**
     * Method for distribute update method for any section by using slug
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     * @return method
     */
    public function sectionUpdate(Request $request, $slug)
    {
        $section = $this->section($slug, 'update');
        return $this->$section($request, $slug);
    }

    //=======================================Blog section Start =====================================
    public function blogView($slug)
    {
        $page_title = "Blog Section";
        $section_slug = Str::slug(SiteSectionConst::BLOG_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;
        $categories = BlogCategory::where('status', 1)->latest()->get();
        $blogs = Blog::latest()->paginate(10);

        return view('admin.sections.setup-sections.blog-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
            'categories',
            'blogs'
        ));
    }
    public function blogUpdate(Request $request, $slug)
    {
        $basic_field_name = ['social_icon' => "required", 'heading' => "required|string|max:100", 'sub_heading' => "required|string|max:255"];

        $slug = Str::slug(SiteSectionConst::BLOG_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went worng! Please try again.')]]);
        }

        return back()->with(['success' => [__('Section updated successfully!')]]);
    }
    public function blogItemStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'       => 'required|integer',
            'en_name'           => "required|string",
            'en_details'        => "required|string",
            // 'tags'              => 'nullable|array',
            // 'tags.*'            => 'nullable|string|max:30',
            // 'image'             => 'required|image|mimes:png,jpg,jpeg,svg,webp',
        ]);


        $name_filed = [
            'name'     => "required|string",
        ];
        $details_filed = [
            'details'     => "required|string",
        ];
        $tags_filed = [
            'tags'     => "required|array",
        ];

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'blog-add');
        }
        $validated = $validator->validate();

        // Multiple language data set

        $language_wise_name = $this->contentValidate($request, $name_filed);
        $language_wise_details = $this->contentValidate($request, $details_filed);
        $language_wise_tags = $this->contentValidate($request, $tags_filed);

        $name_data['language'] = $language_wise_name;
        $details_data['language'] = $language_wise_details;
        $tags_data['language'] = $language_wise_tags;

        $validated['category_id']        = $request->category_id;
        $validated['admin_id']        = Auth::user()->id;
        $validated['name']            = $name_data;
        $validated['details']           = $details_data;
        $validated['slug']            = Str::slug($name_data['language']['en']['name']);
        $validated['lan_tags']          = $tags_data;
        $validated['created_at']      = now();


        // Check Image File is Available or not
        if ($request->hasFile('image')) {
            $image = get_files_from_fileholder($request, 'image');
            $upload = upload_files_from_path_dynamic($image, 'blog');
            $validated['image'] = $upload;
        }

        try {
            Blog::create($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went worng! Please try again')]]);
        }

        return back()->with(['success' => [__('Blog item added successfully!')]]);
    }
    public function blogEdit($id)
    {
        $page_title = "Blog Edit";
        $languages = $this->languages;
        $data = Blog::findOrFail($id);
        $categories = BlogCategory::where('status', 1)->latest()->get();

        return view('admin.sections.setup-sections.blog-section-edit', compact(
            'page_title',
            'languages',
            'data',
            'categories',
        ));
    }
    public function blogItemUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'      => 'required|integer',
            'en_name'       => "required",
            'en_details'     => "required",
            // 'tags'          => 'nullable|array',
            // 'tags.*'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:png,jpg,jpeg,svg,webp',
            'target'        => 'required|integer',
        ]);

        $short_title_field = [
            'short_title'     => "nullable|string"
        ];
        $name_filed = [
            'name'     => "required",
        ];
        $details_filed = [
            'details'     => "required",
        ];
        $tags_filed = [
            'tags'     => "required|array",
        ];

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('modal', 'blog-edit');
        }
        $validated = $validator->validate();
        $blog = Blog::findOrFail($validated['target']);

        // Multiple language data set
        $language_wise_stitle = $this->contentValidate($request, $short_title_field);
        $language_wise_name = $this->contentValidate($request, $name_filed);
        $language_wise_details = $this->contentValidate($request, $details_filed);
        $language_wise_tags = $this->contentValidate($request, $tags_filed);


        $short_title_data['language'] = $language_wise_stitle;
        $name_data['language'] = $language_wise_name;
        $details_data['language'] = $language_wise_details;
        $tags_data['language'] = $language_wise_tags;


        $validated['category_id']        = $request->category_id;
        $validated['admin_id']        = Auth::user()->id;
        $validated['short_title']            = $short_title_data;
        $validated['name']            = $name_data;
        $validated['details']           = $details_data;
        $validated['slug']            = Str::slug($name_data['language']['en']['name']);
        $validated['lan_tags']          = $tags_data;
        $validated['created_at']      = now();

        // Check Image File is Available or not
        if ($request->hasFile('image')) {

            $image = get_files_from_fileholder($request, 'image');
            $upload = upload_files_from_path_dynamic($image, 'blog', $blog->image);
            $validated['image'] = $upload;
        }

        try {
            $blog->update($validated);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went worng! Please try again')]]);
        }

        return back()->with(['success' => [__('Blog item updated successfully!')]]);
    }

    public function blogItemDelete(Request $request)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);

        $blog = Blog::findOrFail($request->target);

        try {
            $image_link = get_files_path('blog') . '/' . $blog->image;
            delete_file($image_link);
            $blog->delete();
        } catch (Exception $e) {

            return back()->with(['error' => [__('Something went worng! Please try again.')]]);
        }

        return back()->with(['success' => [__('BLog delete successfully!')]]);
    }
    public function blogStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->safe()->all();
        $blog_id = $validated['data_target'];

        $blog = Blog::where('id', $blog_id)->first();
        if (!$blog) {
            $error = ['error' => [__('Blog record not found in our system.')]];
            return Response::error($error, null, 404);
        }

        try {
            $blog->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        } catch (Exception $e) {
            $error = ['error' => [__('Something went wrong!. Please try again.')]];
            return Response::error($error, null, 500);
        }

        $success = ['success' => ['Blog status updated successfully!']];
        return Response::success($success, null, 200);
    }
    //=======================================Blog section End ==========================================
    //=======================Category  Section Start=======================
    public function categoryView()
    {
        $page_title = "Setup Blog Category";
        $allCategory = BlogCategory::orderByDesc('id')->paginate(10);
        $languages = Language::get();
        return view('admin.sections.blog-category.index', compact(
            'page_title',
            'allCategory',
            'languages'
        ));
    }
    public function storeCategory(Request $request)
    {
        $basic_field_name = [
            'name'          => "required|string|max:150",
        ];
        $data['language']  = $this->contentValidate($request, $basic_field_name);

        $slugData = Str::slug($data['language']['en']['name']);

        try {
            $admin = Auth::user();
            BlogCategory::create([
                'admin_id'      => $admin->id,
                'name'          => $data['language']['en']['name'],
                'data'          => $data,
                'slug'          => $slugData,
                'created_at'    => now(),
                'status'        => true,
            ]);
        } catch (Exception $e) {

            return back()->with(['error' => [__("Something went wrong! Please try again")]]);
        }
        return back()->with(['success' => [__("Category Saved Successfully!")]]);
    }

    public function categoryUpdate(Request $request)
    {

        $validated = $request->validate([
            'target'    => "required|numeric|exists:blog_categories,id",
        ]);

        $basic_field_name = [
            'name_edit'          => "required|string|max:250",
        ];
        $category = BlogCategory::find($validated['target']);
        $language_wise_data = $this->contentValidate($request, $basic_field_name, "category-update");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $data['language']  = $language_wise_data;
        $slugData = Str::slug($data['language']['en']['name']);
        try {
            $category->update([
                'name'          => $data['language']['en']['name'],
                'data'      => $data,
                'slug'          => $slugData,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => [__("Category Updated Successfully!")]]);
    }
    public function categoryStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->safe()->all();
        $category_id = $validated['data_target'];

        $category = BlogCategory::where('id', $category_id)->first();
        if (!$category) {
            $error = ['error' => [__('Category record not found in our system')]];
            return Response::error($error, null, 404);
        }

        try {
            $category->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        } catch (Exception $e) {
            $error = ['error' => [__('Something went worng!. Please try again')]];
            return Response::error($error, null, 500);
        }

        $success = ['success' => [__('Category status updated successfully!')]];
        return Response::success($success, null, 200);
    }
    public function categoryDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target'        => 'required|string|exists:blog_categories,id',
        ]);
        $validated = $validator->validate();
        $category = BlogCategory::where("id", $validated['target'])->first();

        try {
            $category->delete();
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went worng! Please try again.')]]);
        }

        return back()->with(['success' => [__('Category deleted successfully!')]]);
    }
    public function categorySearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text'  => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }

        $validated = $validator->validate();

        $allCategory = BlogCategory::search($validated['text'])->select()->limit(10)->get();
        return view('admin.components.search.category-search', compact(
            'allCategory',
        ));
    }
    //=======================Category  Section End=======================


    public function rechargeView($slug)
    {
        $page_title = "Recharge Section";
        $section_slug = Str::slug(SiteSectionConst::RECHARGE_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.recharge-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update banner section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function rechargeUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'social_icon' => "required|string|max:100",
            'sub_heading' => "required|max:255",
            'heading' => "required|string|max:50",
        ];

        $slug = Str::slug(SiteSectionConst::RECHARGE_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($request->hasFile("image")) {
            $data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    //footer start
    /**
     * Mehtod for show footer section page
     * @param string $slug
     * @return view
     */
    public function footerView($slug)
    {
        $page_title = "Footer Section";
        $section_slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.footer-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update banner section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'heading' => "required|string|max:100",
            'footer_description' => "required",
            'newsletter_description' => "required",
            'app_description' => "required",
        ];
        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }


        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Mehtod for store footer item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'   => "required|string|max:100",
            'item_social_icon'   => "required|string|max:100",
            'item_link'     => "required|string|max:255",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "footer-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update footer item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerItemUpdate(Request $request, $slug)
    {

        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'   => "required|string|max:100",
            'item_social_icon_edit'   => "required|string|max:100",
            'item_link_edit'     => "required|string|max:255",
        ];

        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "footer-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete services item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function footerItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }
    //footer end

    //contact star

    public function contactView($slug)
    {
        $page_title = "Contact Section";
        $section_slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.contact-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    public function contactUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'title' => "required|string",
            'description' => "required",
            'location' => "required|string|max:255",
            'office_hours' => "required|string|max:255",
            'phone' => "required|string|max:255",
            'email' => "required|email|max:255",
        ];

        $slug = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($request->hasFile("image")) {
            $data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }
    //contact end

    //Header slider start

    /**
     * Mehtod for show slider section page
     * @param string $slug
     * @return view
     */
    public function headerSliderView($slug)
    {
        $page_title = "Header Section";
        $section_slug = Str::slug(SiteSectionConst::HEADER_SLIDERS_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.header-slider-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }


    /**
     * Mehtod for store header slider item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerSliderItemStore(Request $request, $slug)
    {

        $basic_field_name = [
            'item_title'     => "required|string",
            'left_button_text'     => "required",
            'left_button_link'     => "required",
            'right_button_text'     => "required",
            'right_button_link'     => "required",
            'item_description'     => "required",
        ];
        $language_wise_data = $this->contentValidate($request, $basic_field_name, "about-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::HEADER_SLIDERS_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update header slider item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerSliderItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'     => "required|string",
            'left_button_text_edit'     => "required",
            'left_button_link_edit'     => "required",
            'right_button_text_edit'     => "required",
            'right_button_link_edit'     => "required",
            'item_description_edit'     => "required",
        ];

        $slug = Str::slug(SiteSectionConst::HEADER_SLIDERS_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "about-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete header slider item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerSliderItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::HEADER_SLIDERS_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }

    //Header slider end
    //Header floting start

    /**
     * Mehtod for show header floting section page
     * @param string $slug
     * @return view
     */
    public function headerFlotingView($slug)
    {
        $page_title = "Header Floting Section";
        $section_slug = Str::slug(SiteSectionConst::HEADER_FLOTING_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.header-floting-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update header floting section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerFlotingUpdate(Request $request, $slug)
    {
        $basic_field_name = ['heading' => "required|string|max:100", 'description' => "required|string"];

        $slug = Str::slug(SiteSectionConst::HEADER_FLOTING_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);

        $update_data['key']    = $slug;
        $update_data['value']  = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Mehtod for store header floting item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerFlotingItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'social_icon'   => "required|string|max:100",
            'link'          => "required|string|url|max:255",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::HEADER_FLOTING_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update solution item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerFlotingItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'social_icon_edit'   => "required|string|max:100",
            'link_edit'          => "required|string|url|max:255",
        ];

        $slug = Str::slug(SiteSectionConst::HEADER_FLOTING_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete header floting item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function headerFlotingItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::HEADER_FLOTING_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }

    //header floting end
    //Services Started
    /**
     * Mehtod for show services section page
     * @param string $slug
     * @return view
     */
    public function servicesView($slug)
    {
        $page_title = "Services Section";
        $section_slug = Str::slug(SiteSectionConst::SERVICES_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.services-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update services section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function servicesUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'heading' => "required|string",
            'sub_heading' => "required|string|max:255",
            "description" => "required|string|max:255",
            "social_icon" => "required|string|max:255"
        ];

        $slug = Str::slug(SiteSectionConst::SERVICES_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);

        $update_data['key']    = $slug;
        $update_data['value']  = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Mehtod for store services item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function servicesItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'     => "required|string|max:255",
            'item_social_icon'   => "required|string|max:100",
            'item_description'          => "required|string",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "services-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::SERVICES_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update services item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function servicesItemUpdate(Request $request, $slug)
    {

        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_title_edit'     => "required|string|max:255",
            'item_social_icon_edit'   => "required|string|max:100",
            'item_description_edit'          => "required|string",
        ];

        $slug = Str::slug(SiteSectionConst::SERVICES_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete services item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function servicesItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::SERVICES_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }
    //services end

    //about start

    public function aboutView($slug)
    {
        $page_title = "About Section";
        $section_slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.about-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    public function aboutUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'social_icon' => "required",
            'sub_heading' => "required|string|max:255",
            'heading' => "required|string",
            'users' => "required|integer",
            'transactions' => "required|integer",
            'country' => "required|integer",
            'description' => "required",
        ];

        $slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }


        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }
        $section_data['language']  = $this->contentValidate($request, $basic_field_name);



        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    public function aboutItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'     => "required|string|max:255",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "about-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        // if ($request->hasFile("image")) {
        //     $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        // }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    public function aboutItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);
        $basic_field_name = [
            'item_title_edit'     => "required|string|max:255",
        ];

        $slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "about-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    public function aboutItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }
    //about end
    //donetion start

    public function donetionView($slug)
    {
        $page_title = "Donation Section";
        $section_slug = Str::slug(SiteSectionConst::DONETION_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.donetion-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    public function donetionUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'social_icon' => "required",
            'sub_heading' => "required|string|max:255",
            'heading' => "required|string",
            'button_text' => "required",
            'button_link' => "required",
            'description' => "required",
        ];

        $slug = Str::slug(SiteSectionConst::DONETION_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }


        if ($request->hasFile("image")) {
            $section_data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }
        $section_data['language']  = $this->contentValidate($request, $basic_field_name);



        $update_data['value']  = $section_data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    public function donetionItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_title'     => "required|string|max:255",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "about-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::DONETION_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        // if ($request->hasFile("image")) {
        //     $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        // }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    public function donetionItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);
        $basic_field_name = [
            'item_title_edit'     => "required|string|max:255",
        ];

        $slug = Str::slug(SiteSectionConst::DONETION_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "about-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    public function donetionItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::DONETION_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }
    //donetion end

    //whyChoose Start

    public function whyChooseView($slug)
    {
        $page_title = "Why Choose Section";
        $section_slug = Str::slug(SiteSectionConst::WHY_CHOOSE_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.why-choose-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }


    public function whyChooseUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'social_icon' => "required|max:100",
            'heading' => "required|string|max:100",
            'sub_heading' => "required|string|max:255",
            // 'description' => "required",
        ];

        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);

        $update_data['key']    = $slug;
        $update_data['value']  = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    public function whyChooseItemStore(Request $request, $slug)
    {

        $basic_field_name = [
            'item_social_icon'     => "required|max:255",
            'item_title'     => "required|string|max:255",
            'item_description'     => "required",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "whyChoose-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    public function whyChooseItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_social_icon_edit'     => "required|max:255",
            'item_title_edit'     => "required|string|max:255",
            'item_description_edit'     => "required",
        ];


        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);

        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "why-choose-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);


        $section_values['items'][$request->target]['language'] = $language_wise_data;



        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {

            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    public function whyChooseItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::WHY_CHOOSE_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }

    //why choose end

    //Testimonail Started
    public function testimonialView($slug)
    {
        $page_title = "Testimonial Section";
        $section_slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.testimonial-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    public function testimonialUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'heading' => "required|string",
            'sub_heading' => "required|string|max:255",
            // "description" => "required|string|max:255",
            "social_icon" => "required|string|max:255",

        ];

        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);

        $update_data['key']    = $slug;
        $update_data['value']  = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    public function testimonialItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'item_name'     => "required|string|max:255",
            'item_designation'     => "required|string|max:255",
            'item_testimonial_title'     => "required|string|max:255",
            'item_testimonial_rating'     => "required|numeric|min:1|max:5",
            'item_testimonial_description'     => "required",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "testimonial-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";
        $section_data['items'][$unique_id]['created_at'] = now();

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function testimonialItemUpdate(Request $request, $slug)
    {

        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'item_name_edit'     => "required|string|max:255",
            'item_designation_edit'     => "required|string|max:255",
            'item_testimonial_title_edit'     => "required|string|max:255",
            'item_testimonial_rating_edit'     => "required|numeric|min:1|max:5",
            'item_testimonial_description_edit'     => "required",
        ];


        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "testimonial-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }


        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete testimonial item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function testimonialItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }
    //testimonial end

    //faq start

    /**
     * Mehtod for show faq section page
     * @param string $slug
     * @return view
     */
    public function faqView($slug)
    {
        $page_title = "FAQ Section";
        $section_slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.solutions-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update faq section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqUpdate(Request $request, $slug)
    {
        $basic_field_name = ['heading' => "required|string|max:100", 'sub_heading' => "required|string|max:255"];

        $slug = Str::slug(SiteSectionConst::SOLUTIONS_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);

        $update_data['key']    = $slug;
        $update_data['value']  = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Mehtod for store faq item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'title'     => "required|string|max:255",
            'description'     => "required",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "faq-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update faq item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'title_edit'     => "required|string|max:255",
            'description_edit'   => "required",
        ];

        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete faq item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function faqItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::FAQ_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }

    //faq end
    /**
     * Mehtod for show solutions section page
     * @param string $slug
     * @return view
     */
    public function solutionView($slug)
    {
        $page_title = "Solutions Section";
        $section_slug = Str::slug(SiteSectionConst::HEADER_SLIDERS_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.solutions-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update solutions section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function solutionUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'title' => "required|string|max:100",
            'description' => "required|string|max:255"
        ];

        $slug = Str::slug(SiteSectionConst::SOLUTIONS_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }

        $section_data['language']  = $this->contentValidate($request, $basic_field_name);

        $update_data['key']    = $slug;
        $update_data['value']  = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Mehtod for store solution item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function solutionItemStore(Request $request, $slug)
    {
        $basic_field_name = [
            'title'     => "required|string|max:255",
            'description'     => "required",
        ];

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-add");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;
        $slug = Str::slug(SiteSectionConst::SOLUTIONS_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        if ($section != null) {
            $section_data = json_decode(json_encode($section->value), true);
        } else {
            $section_data = [];
        }
        $unique_id = uniqid();

        $section_data['items'][$unique_id]['language'] = $language_wise_data;
        $section_data['items'][$unique_id]['id'] = $unique_id;
        $section_data['items'][$unique_id]['image'] = "";

        if ($request->hasFile("image")) {
            $section_data['items'][$unique_id]['image'] = $this->imageValidate($request, "image", $section->value->items->image ?? null);
        }

        $update_data['key'] = $slug;
        $update_data['value']   = $section_data;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Section item added successfully!']]);
    }

    /**
     * Mehtod for update solution item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function solutionItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'title_edit'     => "required|string|max:255",
            'social_icon_edit'   => "required|string|max:100",
            'link_edit'          => "required|string|url|max:255",
        ];

        $slug = Str::slug(SiteSectionConst::SOLUTIONS_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Mehtod for delete solution item
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function solutionItemDelete(Request $request, $slug)
    {
        $request->validate([
            'target'    => 'required|string',
        ]);
        $slug = Str::slug(SiteSectionConst::SOLUTIONS_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        try {
            $image_link = get_files_path('site-section') . '/' . $section_values['items'][$request->target]['image'];
            unset($section_values['items'][$request->target]);
            delete_file($image_link);
            $section->update([
                'value'     => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section item delete successfully!']]);
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function monitoringView($slug)
    {
        $page_title = "Monitoring Section";
        $section_slug = Str::slug(SiteSectionConst::MONITORING_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;
        return view('admin.sections.setup-sections.monitoring-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update monitoring section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function monitoringUpdate(Request $request, $slug)
    {
        $basic_field_name = [
            'heading' => "required|string|max:100",
            'sub_heading' => "required|string|max:255",
            'button_name' => "required|string|max:50",
            'button_link' => "required|string|url|max:255",
            'desc' => "required|string|max:2000"
        ];

        $slug = Str::slug(SiteSectionConst::MONITORING_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($request->hasFile("image")) {
            $data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function bestItemView($slug)
    {
        $page_title = "Best Item Section";
        $section_slug = Str::slug(SiteSectionConst::BEST_ITEM_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.best-item-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update best item section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function bestItemUpdate(Request $request, $slug)
    {
        $basic_field_name = ['heading' => "required|string|max:100", 'sub_heading' => "required|string|max:255"];

        $slug = Str::slug(SiteSectionConst::BEST_ITEM_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function latestItemView($slug)
    {
        $page_title = "Latest Item Section";
        $section_slug = Str::slug(SiteSectionConst::LATEST_ITEM_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.latest-item-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update best section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function latestItemUpdate(Request $request, $slug)
    {
        $basic_field_name = ['heading' => "required|string|max:100", 'sub_heading' => "required|string|max:255"];

        $slug = Str::slug(SiteSectionConst::LATEST_ITEM_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function glanceView($slug)
    {
        $page_title = "Glance Section";
        $section_slug = Str::slug(SiteSectionConst::GLANCE_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.glance-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    /**
     * Mehtod for update glance section information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function glanceUpdate(Request $request)
    {
        $basic_field_name = ['heading' => "required|string|max:100", 'sub_heading' => "required|string|max:255"];

        $slug = Str::slug(SiteSectionConst::GLANCE_SECTION);
        $section = SiteSections::where("key", $slug)->first();

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Mehtod for update glance section item information
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
     */
    public function glanceItemUpdate(Request $request, $slug)
    {
        $request->validate([
            'target'    => "required|string",
        ]);

        $basic_field_name = [
            'title_edit'     => "required|string|max:255",
            'number_edit'    => "required|numeric",
        ];

        $slug = Str::slug(SiteSectionConst::GLANCE_SECTION);
        $section = SiteSections::getData($slug)->first();
        if (!$section) return back()->with(['error' => ['Section not found!']]);
        $section_values = json_decode(json_encode($section->value), true);
        if (!isset($section_values['items'])) return back()->with(['error' => ['Section item not found!']]);
        if (!array_key_exists($request->target, $section_values['items'])) return back()->with(['error' => ['Section item is invalid!']]);

        $request->merge(['old_image' => $section_values['items'][$request->target]['image'] ?? null]);

        $language_wise_data = $this->contentValidate($request, $basic_field_name, "solution-edit");
        if ($language_wise_data instanceof RedirectResponse) return $language_wise_data;

        $language_wise_data = array_map(function ($language) {
            return replace_array_key($language, "_edit");
        }, $language_wise_data);

        $section_values['items'][$request->target]['language'] = $language_wise_data;

        if ($request->hasFile("image")) {
            $section_values['items'][$request->target]['image']    = $this->imageValidate($request, "image", $section_values['items'][$request->target]['image'] ?? null);
        }

        try {
            $section->update([
                'value' => $section_values,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Information updated successfully!']]);
    }

    /**
     * Method for getting specific step based on incomming request
     * @param string $slug
     * @return method
     */
    public function introView($slug)
    {
        $page_title = "Intro Section";
        $section_slug = Str::slug(SiteSectionConst::INTRO_SECTION);
        $data = SiteSections::getData($section_slug)->first();
        $languages = $this->languages;

        return view('admin.sections.setup-sections.intro-section', compact(
            'page_title',
            'data',
            'languages',
            'slug',
        ));
    }

    public function introUpdate(Request $request, $slug)
    {
        $basic_field_name = ['heading' => "required|string|max:100", 'sub_heading' => "required|string|max:255", 'button_name' => "required|string|max:50", 'button_link' => "required|string|url|max:255", 'video_link' => "required|string|url|max:255", 'desc' => "required|string|max:2000"];

        $slug = Str::slug(SiteSectionConst::INTRO_SECTION);
        $section = SiteSections::where("key", $slug)->first();
        if ($request->hasFile("image")) {
            $data['image']      = $this->imageValidate($request, "image", $section->value->image ?? null);
        }

        $data['language']  = $this->contentValidate($request, $basic_field_name);
        $update_data['value']  = $data;
        $update_data['key']    = $slug;

        try {
            SiteSections::updateOrCreate(['key' => $slug], $update_data);
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }

        return back()->with(['success' => ['Section updated successfully!']]);
    }

    /**
     * Method for get languages form record with little modification for using only this class
     * @return array $languages
     */
    public function languages()
    {
        $languages = Language::whereNot('code', LanguageConst::NOT_REMOVABLE)->select("code", "name")->get()->toArray();
        $languages[] = [
            'name'      => LanguageConst::NOT_REMOVABLE_CODE,
            'code'      => LanguageConst::NOT_REMOVABLE,
        ];
        return $languages;
    }

    /**
     * Method for validate request data and re-decorate language wise data
     * @param object $request
     * @param array $basic_field_name
     * @return array $language_wise_data
     */
    public function contentValidate($request, $basic_field_name, $modal = null)
    {
        $languages = $this->languages();

        $current_local = get_default_language_code();
        $validation_rules = [];
        $language_wise_data = [];
        foreach ($request->all() as $input_name => $input_value) {
            foreach ($languages as $language) {
                $input_name_check = explode("_", $input_name);
                $input_lang_code = array_shift($input_name_check);
                $input_name_check = implode("_", $input_name_check);
                if ($input_lang_code == $language['code']) {
                    if (array_key_exists($input_name_check, $basic_field_name)) {
                        $langCode = $language['code'];
                        if ($current_local == $langCode) {
                            $validation_rules[$input_name] = $basic_field_name[$input_name_check];
                        } else {
                            $validation_rules[$input_name] = str_replace("required", "nullable", $basic_field_name[$input_name_check]);
                        }
                        $language_wise_data[$langCode][$input_name_check] = $input_value;
                    }
                    break;
                }
            }
        }
        if ($modal == null) {
            $validated = Validator::make($request->all(), $validation_rules)->validate();
        } else {
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput()->with("modal", $modal);
            }
            $validated = $validator->validate();
        }

        return $language_wise_data;
    }

    /**
     * Method for validate request image if have
     * @param object $request
     * @param string $input_name
     * @param string $old_image
     * @return boolean|string $upload
     */
    public function imageValidate($request, $input_name, $old_image)
    {
        if ($request->hasFile($input_name)) {
            $image_validated = Validator::make($request->only($input_name), [
                $input_name         => "image|mimes:png,jpg,webp,jpeg,svg",
            ])->validate();

            $image = get_files_from_fileholder($request, $input_name);
            $upload = upload_files_from_path_dynamic($image, 'site-section', $old_image);
            return $upload;
        }

        return false;
    }
}
