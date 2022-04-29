import axios from "axios";
import { getCookie } from "../functions/GetCookie";
import {JWTHandler} from "./JWTHandler";

export const axiosInstance = axios.create({
    baseURL: "http://localhost:5555"
});

axiosInstance.interceptors.request.use(
    req => {
        const token = <string>getCookie("loggerz-token");
        console.log("token", document.cookie);
         let expired = JWTHandler({token, method: "tokenValidity"});

         if (expired) {
            document.cookie = `loggerz-token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
            document.location.reload();
        } 
        
        return req;
    },
    error => {
        console.log(error);
    }
);