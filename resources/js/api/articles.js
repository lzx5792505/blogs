/**
 * Imports the Roast API URL from the config.
 */
import { ROAST_CONFIG } from '../config.js';

export default {
    /**articles
     * GET /api/v1/articles
     */
    getArticles: function(){
        return axios.get( ROAST_CONFIG.API_URL + '/articles' );
    },
    /**
     * GET /api/v1/articles/{articleID}
     */
    getArticle: function( articleID ){
        return axios.get( ROAST_CONFIG.API_URL + '/articles/' + articleID );
    },
}
