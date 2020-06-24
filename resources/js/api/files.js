/**
 * Imports the Roast API URL from the config.
 */
import { ROAST_CONFIG } from "../config.js";

export default {
    /**
     * ---------------------------------------------------------------
     * GET /api/v1/files  获取所有分类列表
     * ---------------------------------------------------------------
     */
    getFiles: function() {
        return axios.get(ROAST_CONFIG.API_URL + "/files");
    }
};
