var num = 0;
setInterval(function(){
	$('.l_li').eq(num).css('display','none');
	$(".l_li").eq(num+1).fadeIn(1500).siblings(".l_li").fadeOut(2000);
	$('.l_li').eq(num+1).css('display','block');
	if(num == 2){
	$('.l_li').eq(num).css('display','none');	
	$(".l_li").eq(0).fadeIn(1500).siblings(".l_li").fadeOut(2000);
	$('.l_li').eq(0).css('display','block');
		num = -1;
	}
	num = num+1;
},3000);

var a = 0;
setInterval(function(){
	$('.kxaj_li').eq(a).css('display','none');
	$(".kxaj_li").eq(a+1).fadeIn(1500).siblings(".kxaj_li").fadeOut(1500);
	$('.kxaj_li').eq(a+1).css('display','block');
	if(a == 5){
	$('.kxaj_li').eq(a).css('display','none');	
	$(".kxaj_li").eq(0).fadeIn(1500).siblings(".kxaj_li").fadeOut(1500);
	$('.kxaj_li').eq(0).css('display','block');
		a = -1;
	}
	a = a+1;
},3000);