<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Feedback;
use App\Models\Learner;
use App\Models\Library;
use App\Models\Page;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function aboutUs(){
        return view('site.about-us');
    }
    public function blog(){
        return view('site.blog');
    }
    public function contactUs(){
        return view('site.contact-us');
    }
    public function privacyPolicy(){
        return view('site.privacy-policy');
    }
    public function termAndCondition(){
        return view('site.term-and-condition');
    }
    public function refundPolicy(){
        return view('site.refund-policy');
    }
    public function home(){
        $subscriptions = Subscription::with('permissions')->get();
        return view('site.home',compact('subscriptions'));
    }
    public function searchLibrary(){
        $cities = City::pluck('city_name', 'id');
        $topLibraries = Library::take(5)->get();
        $library_count=Library::count();
        $learner_count=Learner::count();
        $city_count=City::count();
        $feedback_count=Feedback::count();
        $happy_customer=Feedback::leftJoin('libraries','feedback.library_id','=','libraries.id')->leftJoin('cities','cities.id','libraries.city_id')->where('feedback.rating','>',4)->select('libraries.library_address','libraries.created_at','feedback.*','cities.city_name')->get();
       
        return view('site.library-directory' ,compact('cities','topLibraries','learner_count','library_count','city_count','happy_customer','feedback_count'));
    }
    public function listPage(){
        $pages = Page::all();
        return view('administrator.indexpage',compact('pages'));
    }

    public function createpage(){
        return view('administrator.createpage');
    }
    public function editPage($id)
    {
       
        $page = Page::findOrFail($id);

        return view('administrator.createpage', compact('page'));
    }
    public function pageStore(Request $request, $id = null)
    {
        // Validation
        $data =$request->validate([
            'page_title' => 'required|string|max:255',
            'page_slug' => 'required|string|max:255|unique:pages,page_slug,' . $id,
            'page_content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'meta_og' => 'nullable|string',
        ]);

        // If $id exists, update the existing page
        if ($id) {
            $page = Page::findOrFail($id); // Find the page by ID, or fail if not found
            $page->update( $data);
            $message = 'Page updated successfully!';
        } else {
            // If $id does not exist, create a new page
            Page::create($data );
            $message = 'Page Crete successfully!';
        }

        // Redirect or return with success message
        return redirect()->route('add-page')->with('success',$message);
    }

    public function createBlog(){
        $categories=Category::get();
        return view('administrator.addBlog',compact('categories'));
    }

   
    public function blogStore(Request $request, $id = null)
    {
       
        $data = $request->validate([
            'page_title' => 'required|string|max:255',
            'page_slug' => 'required|string|max:255|unique:blogs,page_slug,' . $id,
            'page_content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'meta_og' => 'nullable|string',
            'header_image' => $id ? 'nullable|image|mimes:jpg,jpeg,png|max:2048' : 'required|image|mimes:jpg,jpeg,png|max:2048',
    
            'categories_id' => 'nullable|array', // For the multiple-select dropdown
            'categories_id.*' => 'nullable|integer|exists:categories,id',
        ]);
    
        // Handle categories
        $categoryIds = [];
        if ($request->categories) {
            $categories = json_decode($request->categories, true);
    
            foreach ($categories as $categoryName) {
                if (isset($categoryName['value'])) {
                    $category = Category::firstOrCreate(['name' => trim($categoryName['value'])]);
                    $categoryIds[] = $category->id; // Collect the category ID
                }
            }
        }
    
        if (!empty($categoryIds)) {
            $data['categories_id'] = json_encode($categoryIds); // Store all category IDs as a JSON array
        }
    
        // Handle tags
        $tags = [];
        if ($request->tags) {
            $decodedTags = json_decode($request->tags, true);
    
            foreach ($decodedTags as $tag) {
                if (isset($tag['value'])) {
                    $tags[] = $tag['value']; // Add only the tag value to the array
                }
            }
        }
    
        if (!empty($tags)) {
            $data['tags'] = json_encode($tags); // Save tags as a JSON array like ["tag one", "tag two"]
        }
    
        // Handle header image upload
        if ($request->hasFile('header_image')) {
            $data['header_image'] = $request->file('header_image')->store('blog_images', 'public');
        }
    
        // Save or update the blog
        $blog = $id ? Blog::findOrFail($id) : new Blog();
        $blog->fill($data);
        $blog->save();
    
        $message = $id ? 'Blog updated successfully!' : 'Blog created successfully!';
        return redirect()->route('add-page')->with('success', $message);
    }

    public function listBlog(){
        $blogs = Blog::all();
        return view('administrator.indexblog',compact('blogs'));
    }
    

    
}
