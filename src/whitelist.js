if (MyRecipesBlocksBlocksAjax.whitelist.length > 0) {
    var myRecipesBlocksAllowedBlocks = JSON.parse(MyRecipesBlocksBlocksAjax.whitelist);
    function myRecipesBlocksWhitelistBlocks(settings, name) {
    	if (myRecipesBlocksAllowedBlocks.length !== 0) {
    		if (myRecipesBlocksAllowedBlocks.indexOf(name) === -1 && name.indexOf('my-recipes-blocks') !== 0) {
    			if (typeof settings.supports === 'undefined') {
    				settings.supports = {};
    			}
    			settings.supports.inserter = false;
    		}
    	}
    
    	return settings;
    }
    
    wp.hooks.addFilter(
    	'blocks.registerBlockType',
    	'my-recipes-blocks',
    	myRecipesBlocksWhitelistBlocks
    );
}