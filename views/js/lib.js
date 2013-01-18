/**
 * Created with JetBrains PhpStorm.
 * User: nigel.daley
 * Date: 1/14/13
 * Time: 12:20 PM
 * To change this template use File | Settings | File Templates.
 */


M.xcrisearch_standard_functions = {

    init:    function() {
        cbuttons	=	YAHOO.util.Dom.getElementsByClassName('changebutton');

        for(i=0;i <cbuttons.length;i++)	{
            console.log(cbuttons[i].id);
            YAHOO.util.Event.on(cbuttons[i], 'click', M.xcrisearch_standard_functions.showhide,cbuttons[i]);
            YAHOO.util.Dom.addClass(cbuttons[i].id+"_details", 'hidedetail');
        }
    },

    showhide:   function(e,ele)     {
        //YAHOO.util.Dom.setStyle(ele, 'display', 'none');
        if (YAHOO.util.Dom.hasClass(ele.id+"_details", 'showdetail'))	{
            YAHOO.util.Dom.removeClass(ele.id+"_details", 'showdetail');
            YAHOO.util.Dom.addClass(ele.id+"_details", 'hidedetail');

            YAHOO.util.Dom.removeClass(ele.id, 'minus');
            YAHOO.util.Dom.addClass(ele.id, 'plus');
        } else {
            YAHOO.util.Dom.removeClass(ele.id+"_details", 'hidedetail');
            YAHOO.util.Dom.addClass(ele.id+"_details", 'showdetail');

            YAHOO.util.Dom.removeClass(ele.id, 'plus');
            YAHOO.util.Dom.addClass(ele.id, 'minus');
        }
        element	=	YAHOO.util.Dom.get(ele.id+"_details");
    }
}