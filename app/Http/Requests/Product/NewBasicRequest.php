<?php

namespace App\Http\Requests\Product;

use App\Exceptions\RequestException;
use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class NewBasicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'short_desc' => 'required|string|max:70',
            //'rules' => 'required|string',
            //'quantity' => 'required|numeric|min:1',
            //'mesure' => 'required|string|max:10',
            //'coins' => 'required|array',
            //'types' => 'required|array',
        ];
    }

    public function persist(Product $currentProduct = null)
    {

        // make new product
        $editingProduct = $currentProduct ?? new Product;

        $editingProduct->category_id = $this->category_id;
        if(is_null($currentProduct)) $editingProduct->user_id = auth()->user()->id; // set user id for new products
        $editingProduct->name = $this->name;
        $editingProduct->description = $this->description;
        $editingProduct->short_desc = $this->short_desc;
        $editingProduct->rules = $this->rules;
        $editingProduct->mesure = $this->mesure;


        // edit quantity if it is not autodelivery
        if(!$editingProduct -> isAutodelivery())
            $editingProduct->quantity = $this->quantity;
        // if editing product
        if ($currentProduct && $currentProduct->exists()) {
            // save
            $editingProduct->save();

            session()->flash('success', 'Успешно изменено!');
            // return to back
            return redirect()->back();
        }
        // if making new product
        // generate new id
        $editingProduct->id = \Uuid::generate()->string;
        // put in session
        session()->put('product_adding', $editingProduct);

        return redirect()->route('profile.vendor.product.offers');

    }
}
