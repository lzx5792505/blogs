import { ROAST_CONFIG } from '../config.js';

export default {
    getCategories:function () {
        return axios.get(ROAST_CONFIG.API_URL+'/categories',{

        });
    },

}
