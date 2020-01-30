<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use Intervention\Image\Gd\Decoder;

class ProductsController extends Controller
{
    public function addProduct(Request $request){
   
        if($request->isMethod('post')){
            $data = $request->all();
          
            $product = new Product();
            if(empty($data['category_id'])){
                return redirect()->back()->with('flash_message_error', 'Under category is missing!');
            }else{
                $product->category_id = $data['category_id'];
            }
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if(!empty($data['description'])){
                $product->description = $data['description'];
            }else{
                $product->description = "";
            }
            if(!empty($data['care'])){
                $product->care = $data['care'];
            }else{
                $product->care = "";
            }
            $product->price = $data['price'];
            
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                  $extension = $image_tmp->getClientOriginalExtension();
                  $filename = rand(111,99999).'.'.$extension;
                  $large_image_path = 
                'images/backend_images/products/large/'.$filename;
                  $medium_image_path = 
                 'images/backend_images/products/medium/'.$filename;
                  $small_image_path = 
                'images/backend_images/products/small/'.$filename;
                  // Resize Images
                  Image::make($image_tmp)->save($large_image_path);
                  Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                  Image::make($image_tmp)->resize(300,300)->save($small_image_path);
              
                  // Store image name in products table
                  $product->image = $filename;
                  }
                 }

            $product->save();
            return redirect('admin/view-products')->with('flash_message_success', 'Product added successfully!');

        }
// Category dropdown start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
           // echo "<pre>"; print_r($sub_categories); die;
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
               //  echo "<pre>"; print_r($sub_cat); die;
            }
        }
// Category dropdown end
        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request,$id=null){
    	//update data 
    	if($request->isMethod('post')){
    		$data=$request->all();
      
    			if($request->hasFile('image')){
    			$image_tmp=Input::file('image');
    			// echo $image_tmp; die;
    			if($image_tmp->isValid()){
    				// echo "test";die;
    				$extention=$image_tmp->getClientOriginalExtension();
    				$filename=rand(111,99999).'.'.$extention;
    				$large_image_path='images/backend_images/products/large/'.$filename;
    				$medium_image_path='images/backend_images/products/medium/'.$filename;
    				$small_image_path='images/backend_images/products/small/'.$filename;
    				Image::make($image_tmp)->save($large_image_path);
    				Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
    				Image::make($image_tmp)->resize(300,300)->save($small_image_path);
    			}
    			
		}
		else{
    				$filename=$data['current_image'];
    			}
		         if(empty($data['description'])){
		         	$data['description']='';
                 }
                 if(empty($data['care'])){
                    $data['care']='';
                }

    		$abc = Product::where(['id'=>$data['id']])->update(['category_id'=>$data['category_id'],'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'description'=>$data['description'],'care'=>$data['care'],'price'=>$data['price'],'image'=>$filename]);

    		return redirect('admin/view-products')->with('flash_message_success','Product Updared Successfully!');
    	}
    	// end of update data
    	// to get category with their sub category
    	$productDetails=Product::where(['id'=>$id])->first();
    	$category=Category::where(['parent_id'=>0])->get();
    	$category_dropdwon="<option value='' seleted disabled>Select</option>";
    	foreach($category as $cat){
    		if($cat->id==$productDetails->category_id){
    			$selected="selected";
    		}
    		else{
    			$selected="";
    		}
    		$category_dropdwon.="<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
    		$sub_category=Category::where(['parent_id'=>$cat->id])->get();
    		foreach ($sub_category as $sub_cat) {
    			if($sub_cat->id==$productDetails->category_id){
    				$selected="selected";
    			}
    			else{
    				$selected="";
    			}
    			$category_dropdwon.="<option value='".$sub_cat->id."' ".$selected.">&nbsp; --&nbsp;".$sub_cat->name."</option>";
    		}

    	}
    	return view('admin.products.edit_product')->with(compact('productDetails','category_dropdwon'));
    }
    
//     public function editProduct(Request $request, $id=null){

//        if($request->isMethod('post')){
//          $data = $request->all();


//         //  $product->category_id = $request->category_id;
//         //  $product->product_name = $request->product_name;
//         //  $product->product_code = $request->product_code;
//         //  $product->product_color = $request->product_color;
//         //  if(!empty($product->description)){
//         //     $product->description = $request->description;
//         //  }else{
//         //     $product->description = "";
//         //  }

//         //  $product->price = $request->price;

//         //  if($request->hasFile('image')){
//         //     $image_tmp = Input::file('image');
//         //     if($image_tmp->isValid()){
//         //       $extension = $image_tmp->getClientOriginalExtension();
//         //       $filename = rand(111,99999).'.'.$extension;
//         //       $large_image_path = 
//         //     'images/backend_images/products/large/'.$filename;
//         //       $medium_image_path = 
//         //      'images/backend_images/products/medium/'.$filename;
//         //       $small_image_path = 
//         //     'images/backend_images/products/small/'.$filename;
//         //       // Resize Images
//         //       Image::make($image_tmp)->save($large_image_path);
//         //       Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
//         //       Image::make($image_tmp)->resize(300,300)->save($small_image_path);
              
//         //       }
//         //      }else{
//         //         $fileName = $request->current_image;
//         //      }

//         //      $product->image = $filename = "";  
             
            

//          if($request->hasFile('image')){
//             $image_tmp = Input::file('image');
//          //  dd($image_tmp);
//             if($image_tmp->isValid()){
               
//               $extension = $image_tmp->getClientOriginalExtension();
//               $filename = rand(111,99999).'.'.$extension;
//               $large_image_path = 
//             'images/backend_images/products/large/'.$filename;
//               $medium_image_path = 
//              'images/backend_images/products/medium/'.$filename;
//               $small_image_path = 
//             'images/backend_images/products/small/'.$filename;
//               // Resize Images
//               Image::make($image_tmp)->save($large_image_path);
//               Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
//               Image::make($image_tmp)->resize(300,300)->save($small_image_path);
//            //   $product->save();
          
//               }
//              }
//              else{
//               $filename = $data['current_image'];
//               }
//       if(empty($data['description'])){
//           $data['description'] = '';
//       }
      
// //      $abc =   Product::where(['id'=>$id])->update(['category_id'=> $data['category_id'],'product_name'=> $data['product_name'],'product_code'=> $data['product_code'], 'product_color'=>$data['product_color'], 'description'=>$data['description'], 'price'=>$data['price'], 'image'=>$filename]);  
// // dd($abc);

//        $abc = Product::where(['id'=>$id])->update([
//             'category_id'=>$data['category_id'],
//             'product_name'=>$data['product_name'],
//             'product_code'=>$data['product_code'],
//             'product_color'=>$data['product_color'],
//             'description'=>$data['description'],
//             'price'=>$data['price'],
//             'image'=>$filename]);
//             dd($abc);
//          return redirect()->back()->with('flash_message_success', 'Product has been updated successfully!');

//        }

//        $productDetails = Product::where(['id'=>$id])->first();
//        // Category dropdown start
//        $categories = Category::where(['parent_id'=>0])->get();
//        $categories_dropdown = "<option selected disabled>Select</option>";
//        foreach($categories as $cat){
//            if($cat->id == $productDetails->category_id){
//                $selected = 'selected'; 
//            }else{
//                $selected = "";
//            }
//            $categories_dropdown .= "<option value='".$cat->id."'".$selected.">".$cat->name."</option>";
//            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
//            foreach($sub_categories as $sub_cat){
//             if($sub_cat->id == $productDetails->category_id){
//                 $selected = 'selected'; 
//             }else{
//                 $selected = "";
//             }
//                $categories_dropdown .= "<option value = '".$sub_cat->id."'".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
//            }
//        }
// // Category dropdown end

//       return view('admin.products.edit_product', compact('productDetails','categories_dropdown'));
//     }

    public function viewProducts(){
        $products = Product::orderBy('id','DESC')->get();
        foreach($products as $key => $vel){
            $category_name = Category::where(['id'=>$vel->category_id])->first();
            if($category_name){
                $products[$key]->category_name = $category_name->name;
            }
           
        }
        return view('admin.products.view_products', compact('products'));
    }

    public function deleteProductImage($id = null){

        $productImage = Product::where(['id'=>$id])->first();

        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success', 'Product image has been deleted successfully!');
        
    }
    public function deleteProduct($id = null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product has been deleted successfully!');
    }
    public function addAttributes(Request $request, $id){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
      //  $productDetails = json_decode(json_encode($productDetails));
        //dd($productDetails);
        if($request->isMethod('post')){
           $data = $request->all();
        //   dd($data);
           foreach($data['sku'] as $key => $val){
            if(!empty($val)){
                $attrCountSKU = ProductsAttribute::where('sku', $val)->count();
                if($attrCountSKU>0){
                    return redirect('admin/add-attributes/'.$id)->with('flash_message_error', 'SKU already exisit. Please add another SKU!');

                }
                $attrCountSizes = ProductsAttribute::where(['product_id'=>$id, 'size'=> $data['size'][$key]])->count();
                if($attrCountSizes>0){
                    return redirect('admin/add-attributes/'.$id)->with('flash_message_error', '"' .$data['size'][$key]. '" size already exisit. Please add another Size!');
                    
                }
                $attribute = new ProductsAttribute;
                $attribute->product_id = $id;
                $attribute->sku = $val;
                $attribute->size = $data['size'][$key];
                $attribute->price = $data['price'][$key];
                $attribute->stock = $data['stock'][$key];
                $attribute->save();
            }
           }
           return redirect('admin/add-attributes/'.$id)->with('flash_message_success', 'Product attributes has been added successfully!');
        }
        return view('admin.products.add_attributes', compact('productDetails'));
    }
    public function deleteAttribute($id = null) {
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Attribute has been deleted successfully!');
    }

    // Frontend controller 

    public function products($url)
    {

        $countCategories = Category::where(['url'=> $url, 'status'=>1])->count();
        if($countCategories==0){
            abort(404);
        }

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
       // dd($categories);
        $categoryDetails = Category::where(['url'=>$url])->first();

        if($categoryDetails->parent_id==0){
        $subcategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
     //   $cat_ids = "";
        foreach($subcategories as $subcat){
            $cat_ids[] = $subcat->id;  
     }
     $productsAll = Product::whereIn('category_id', $cat_ids)->get();

        }
        else
        {
        $productsAll = Product::where(['category_id'=> $categoryDetails->id])->get();
        }

      //  dd($productsAll);
        return view('products.listing', compact('categories','categoryDetails','productsAll'));
    }
    public function product($id = null)
    {
        $productDetails = Product::with('attributes')->where('id',$id)->first();
        $productDetails = json_decode(json_encode($productDetails));
       // echo "<pre>"; print_r($productDetails); die;
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        return view('products.detail',compact('productDetails','categories'));
    }

    public function getProductPrice(Request $request)
    {
          $data = $request->all();
          $proattr = explode("-",$data['idSize']);
          $proattr = ProductsAttribute::where(['product_id'=>$proattr[0], 'size'=>$proattr[1]])->first();
          echo $proattr->price;
    }
}




