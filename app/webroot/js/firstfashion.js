function checkForJSAction()
{
	var qs = new Querystring();
	var jsaction = qs.get("jsaction");
	var jsparams = qs.get("jsparams");
	//var jsparams_list = jsparams.split(",");
	if (jsaction)
	{
		eval(jsaction + "(" + jsparams  + ")");
	}
}

function viewmessage(msg_id)
{
	Shadowbox.open({
        	player:     'iframe',
        	title:      'Message Center',
        	content:    '/member_messages/view/'+msg_id,
        	height:     350,
        	width:      350
    	});
}

function textareaCounter(event, maxlimit)
{
	var element = event.element();
	var value = element.getValue();
	var length = value.length;
	if(length >= maxlimit){
		element.value = value.substring(0, maxlimit);
	}
	
}

function toggleMasterCheckbox(obj, classname) {
	var toggle = obj.checked;
        $$('.'+classname).each(
        	function(check) {
        		check.checked = toggle;
                }
        );
}       

function reloadSignupForm(mem_sel)
{
	var mem_type = $F(mem_sel);
	//mem_sel.options[mem_sel.selectedIndex].value;
	document.location.href = "/members/signup/"+mem_type;
}

function updateSearchRangeField(opfield)
{
	var op = $F(opfield);
	var opid = opfield.id;
	var fieldprefix = opid.replace(/Op$/, "");

	var field2 = $(fieldprefix);

	if (op == 'between')
	{
		field2.show();
		//field2.style.display = 'block';
	} else {
		field2.hide();
	}
}

function refineMemberSearch(typefield)
{
	var memtype = $F(typefield);
	var url = "/members/search_advanced?member_type="+memtype;
	document.location.href = url;
}
