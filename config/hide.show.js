$(function(){
$("#button_show_product_description").click(function () 
{
var value = this.value;
if (value == "Show") 
    {
    this.value = "Hide"
    $("#product_description").show();
    } 
else
    {
    this.value = "Show"
    $("#product_description").hide();
    }
}
);

$("#button_show_carton_description").click(function () 
{
var value = this.value;
if (value == "Show") 
    {
    this.value = "Hide"
    $("#carton_description").show();
    } 
else
    {
    this.value = "Show"
    $("#carton_description").hide();
    }
}
);

$("#button_show_shipment_description").click(function () 
{
var value = this.value;
if (value == "Show") 
    {
    this.value = "Hide"
    $("#shipment_description").show();
    } 
else
    {
    this.value = "Show"
    $("#shipment_description").hide();
    }
}
);

$("#button_show_return_description").click(function () 
{
var value = this.value;
if (value == "Show") 
    {
    this.value = "Hide"
    $("#return_description").show();
    } 
else
    {
    this.value = "Show"
    $("#return_description").hide();
    }
});



});