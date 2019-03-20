(function($) 
 {
    tinymce.create("tinymce.plugins.wp_sugarscale_button",
	{
		init: function(ed, url) 
		{
			ed.addButton("sugarscale", 
			{
                title : "Sugarscale",
                cmd : "sugarscale_open",
                image : url + "/scale_black.svg"
            });

            ed.addCommand("sugarscale_open", function() 
			{
                ed.windowManager.open({
					title: "Insert Shortcode",
					width: $(window).width() * 0.6,
					height: 48,
					inline: 1,
					id: "sugarscale_dialog",
					buttons: [{
						text: "Insert",
						id: "sugarscale_insert_button",
						class: "insert",
						onclick: function(event)
						{
							var container = $(event.target).parents("#sugarscale_dialog");
							var amt = container.find(".amt").val() || "";
							var units = container.find(".units").val() || "";
							var sweetener = container.find(".sweeteners").val() || "";
							ed.execCommand("mceInsertContent", 0, '[sugarscale amt="' + amt + '" units="' + units + '" sweetener="' + sweetener + '"]');
							$("#sugarscale_cancel_button").click();
						},
					},
					{
						text: "Cancel",
						id: "sugarscale_cancel_button",
						onclick: "close"
					}],
				});
				
				$("#sugarscale_dialog-body").append($("#sugarscale_dialog_contents").clone().show());
            });

        },
        createControl: function(n, cm) 
		{
            return null;
        },
        getInfo: function() 
		{
            return {
                longname : "WP-Sugarscale",
                author : "Matt Martin",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("wp_sugarscale_button", tinymce.plugins.wp_sugarscale_button);
 })(jQuery);