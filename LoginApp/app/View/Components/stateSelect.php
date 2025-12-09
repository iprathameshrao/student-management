<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class stateSelect extends Component
{
    public $states;
public $selected;
public $name;
    /**
     * Create a new component instance.
     */
    public function __construct($selected = null, $name = 'state'){
        $this->selected = $selected;
        $this->name = $name;

        $this->states = [
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar","Chhattisgarh", "Goa", "Gujarat", "Haryana", 
            "Himachal Pradesh","Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh","Maharashtra", "Manipur", "Meghalaya", 
            "Mizoram", "Nagaland","Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu","Telangana", "Tripura", 
            "Uttar Pradesh", "Uttarakhand","West Bengal",
            // UT
            "Andaman and Nicobar Islands", "Chandigarh","Dadra and Nagar Haveli and Daman and Diu","Delhi", "Jammu and Kashmir", 
            "Ladakh","Lakshadweep", "Puducherry"
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.state-select');
    }
}
