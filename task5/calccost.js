function calccost()
{
    let count=document.getElementsByName("count");
    let product=document.getElementsByName("product");
    let res=document.getElementById("result");
    let result=count[0].value*product[0].value;
    if(isNaN(result))
    {
        alert("ERROR!!! Введите число!")
        return false;
    }
    res.innerHTML=result+" руб.";

    return false;
}
