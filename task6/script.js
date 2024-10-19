function getprice()
{
    return {pType:[999, 2999, 4999],
        pDelivery:[300, 100, 0],
        pOption:[0, 100],
        pProp:[500, 1000]
    }
}

function updateprice()
{
    let s=document.getElementsByName("product");
    let select=s[0];
    let prices=getprice();
    let count=document.getElementsByName("count");
    let price=prices.pType[(select.value)-1]*count[0].value;

    let OpDisplay=document.getElementById("options");
    let PrDisplay=document.getElementById("checkboxes");
    let checkboxes=document.querySelectorAll("#checkboxes input");
    let radios=document.getElementsByName("rad2");

    if(select.value==1)
    {    OpDisplay.style.display="none";
        PrDisplay.style.display="none";
        checkboxes.forEach(function(checkbox){
            checkbox.checked=0;
        })
            radios[0].checked=1;
        
    }
    if(select.value==2)
    {
        OpDisplay.style.display="flex";
        PrDisplay.style.display="none";
        checkboxes.forEach(function(checkbox){
            checkbox.checked=0;
        })
    }
    if(select.value==3)
        {
            OpDisplay.style.display="none";
            PrDisplay.style.display="flex";
            radios[0].checked=1;
        }
    
    let delivery = document.getElementsByName("rad1");
    delivery.forEach(function(radio){
        if(radio.checked && prices.pDelivery[radio.value-1]!==undefined)
            price+=prices.pDelivery[radio.value-1];
    })
    let options=document.getElementsByName("rad2");
    options.forEach(function(radio){
        if(radio.checked && prices.pOption[radio.value-1]!==undefined)
            price+=prices.pOption[radio.value-1]*count[0].value;
    })
    let property=document.querySelectorAll("#checkboxes input");
    property.forEach(function(checkbox){
        if(checkbox.checked && prices.pProp[checkbox.value-1]!==undefined)
            price+=prices.pProp[checkbox.value-1]*count[0].value;
    })
    let res=document.getElementById("result");
    if(isNaN(price))
    {alert("Введите число");}
    else
    {res.innerHTML=price+" рублей";}
    
}

window.addEventListener("DOMContentLoaded", function(event)
{
    let c=document.getElementsByName("count");
    let count=c[0];
    count.addEventListener("change", function(event)
{
    updateprice();
})
    



    let s=document.getElementsByName("product");
    let select=s[0];
    select.addEventListener("change", function(event){
        updateprice();
    })

    let delivery = document.getElementsByName("rad1");
    delivery.forEach(function(radio){
        radio.addEventListener("change",function(event)
    {
        updateprice();
    })})
    let options=document.getElementsByName("rad2");
    options.forEach(function(radio){
        radio.addEventListener("change", function(event){
            updateprice();
        })
    })
    let property=document.querySelectorAll("#checkboxes input");
    property.forEach(function(checkbox){
        checkbox.addEventListener("change", function(event){
            updateprice();
        })
    })

    updateprice();
})
