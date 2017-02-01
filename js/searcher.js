/**
 *  This javascript for visual effects and ajax requests of the application.
 *  @author David Andrés Manzano Herrera - Universidad Icesi
 *  @since 2015-12-16
 **/

/** Avoid conflicts with another javascript libraries */
jQuery.noConflict();


/** Executing the onready operations*/
jQuery(document).ready(function () {
    jQuery("#apprecord").css("display", "none");
    jQuery("#appresults").css("display", "none");
    jQuery("#dspaceSearchForm input:button").button();

    jQuery("#dspaceSearchForm #query").blur(function () {
        if (jQuery(this).val() == "" || jQuery(this).val() == null) {
            jQuery(this).val("Digita el tema a buscar...");
            jQuery(this).attr('style', 'color:#cccccc;');
        }
        ;
    });

    jQuery("#dspaceSearchForm #query").focus(function () {
        if (jQuery(this).val() == "Digita el tema a buscar...") {
            jQuery(this).val("");
            jQuery(this).attr('style', 'color:#000000;');
        }
    });

    jQuery("#scope_community option, #11522_3647 option").each(function () {
        data_level = jQuery(this).attr("data-level");
        if (data_level == 0 || data_level == 1) {
            jQuery(this).css("font-weight", "bold");
            jQuery(this).css("background", "#eee");
            if (data_level == 1) {
                jQuery(this).css("padding-left", "3px");
            }
        }

        if (data_level == "leaf") {
            jQuery(this).css("padding-left", "6px");
        }
    });

    jQuery("#scope_community").change(function () {
        jQuery("#11522_3647").hide();
        scope_community = jQuery(this).val();
        community_id = scope_community.replace("/", "_", "gi");
        jQuery("#" + community_id).show();
        scope = scope_community;
    });

    jQuery("#11522_3647").change(function () {
        scope = jQuery(this).val();
    });

    /**
     This method manage the click event for applicarion's search button.
     @modified damanzano 2012-06-28 Add content for #serachnote.
     */
    jQuery("#search").click(function () {

        if (validate()) {
            search_scope = scope;
            jQuery("#searchnote").html("Estas buscando <span class=\"rednote\">\"" + jQuery("#query").val() + "\"</span> en <span class=\"rednote\">" + search_scope_name + "</span>");
            openSearchRequest(jQuery("#query").val(), search_scope, jQuery("#rpp").val(), jQuery("#start").val(), jQuery("#sort_by").val(), jQuery("#order").val(), false);
        }
    });

    jQuery("#dspaceSearchForm").submit(function (event) {
        event.preventDefault();
        if (validate()) {
            search_scope = scope;
            openSearchRequest(jQuery("#query").val(), scope, jQuery("#rpp").val(), jQuery("#start").val(), jQuery("#sort_by").val(), jQuery("#order").val(), false);
        }
    });
});

/**
 * Ajax call for a open search request.
 * @author damanzano
 * @since 2015-12-16
 * @param string query
 * @param string scope
 * @param int rpp
 * @param int start
 * @param int sort_by
 * @param string order
 **/
function openSearchRequest(query, scope, rpp, start, sort_by, order) {
    flipdiv(jQuery("#apprecord"), jQuery("#appresults"));
    jQuery("#appresults").html("<div class=\"loader\"><img src=\"images/loader.gif\"/><div>");
    jQuery.ajax({
        type: "POST",
        url: "view/HtmlResults.php",
        dataType: "html",
        data: {
            query: query,
            scope: scope,
            rpp: rpp,
            start: start,
            sort_by: sort_by,
            order: order
        },
        success: function (html) {
            jQuery("#appresults").html(html);

            jQuery(".dspace-item").click(function (event) {
                event.preventDefault();
                var dspaceItemId = jQuery(this).attr("data-dspaceitemid");
                flipdiv(jQuery("#appresults"), jQuery("#apprecord"));

                getRecord(dspaceItemId, jQuery("#query").val(), scope, jQuery("#rpp").val(), jQuery("#start").val(), jQuery("#sort_by").val(), jQuery("#order").val());

            });

            jQuery("a.app_navlink, a.app_navlink_active, a.app_navlink_disabled").button();
            jQuery("a.app_navlink_disabled").addClass("ui-state-disabled");
        }
    });

}

function loadBDResults(start, rpp, query, nav_call) {
    openSearchRequest(query, search_scope, rpp, start, jQuery("#sort_by").val(), jQuery("#order").val());
}
/**
 * Validate whether the query input is not empty
 * @author damanzano
 * @since 2015-12-16
 **/
function validate() {
    var query = jQuery("#dspaceSearchForm #query").val();
    category = "";
    data_level = "";
    jQuery("select[name=scope_categories]").each(function () {
        if (jQuery(this).is(":visible")) {
            category = jQuery(this).find(":selected").text();
            search_scope_name = category;
            data_level = jQuery(this).find(":selected").attr("data-level");
        }

    });

    if (query == null || query == "" || query == "Digita el tema a buscar...") {

        if (scope == "11522/3647" || data_level == 0 || data_level == 1) {

            alert("Debes refinar tu busqueda ingresando el tema que deseas consultar");
            return false;
        } else {
            /*jQuery("#dspaceSearchForm #query").attr('style', 'color:#000000;');*/
            jQuery("#dspaceSearchForm #query").val(category);
            return true;
        }

    } else {
        return true;
    }
}

/**
 * Ajax call to a oai GetRecord request
 * @author damanzano
 * @since 2015-12-16
 * @param string identifier
 * @param string query
 * @param string scope
 * @param int rpp
 * @param int start
 * @param int sort_by
 * @param string order
 **/
function getRecord(identifier, query, scope, rpp, start, sort_by, order) {
    jQuery("#apprecord").html("<div class=\"loader\"><img src=\"images/loader.gif\"/><div>");
    jQuery.ajax({
        type: "POST",
        url: "view/HtmlRecord.php",
        dataType: "html",
        data: {
            identifier: identifier,
            query: query,
            scope: scope,
            rpp: rpp,
            start: start,
            sort_by: sort_by,
            order: order
        },
        success: function (html) {
            jQuery("#apprecord").html(html);
            jQuery("a.app_navlink, a.app_navlink_active").button();

            jQuery(".back").button({
                icons: {
                    primary: "ui-icon ui-icon-carat-1-w"
                }
            }).click(function (event) {
                flipdiv(jQuery("#apprecord"), jQuery("#appresults"));
            });
        }
    });
}

/**
 * Flip from one html element to other. Hides the from element and shows the to element
 * @author damanzano
 * @since 2015-12-20
 * @param mixed from The visible element
 * @param mixed to The hidden element
 **/
function flipdiv(from, to) {
    from.hide(
            "slide",
            {
                easing: "easeOutExpo"
            },
            "fast",
            function () {
                to.show(
                        "slide",
                        {
                            easing: "easeInExpo"
                        },
                        "fast"
                        );
            });
}
