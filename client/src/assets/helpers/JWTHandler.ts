import { isExpired, decodeToken } from "react-jwt";

type JWTHandlerProps = {
    token: string;
    method: string;
};

export const JWTHandler = ({token, method}: JWTHandlerProps) => {
    const myDecodedToken = <any>decodeToken(token);
    const isMyTokenExpired = <boolean>isExpired(token);
    
    console.log(token);
    console.log("qqqqqqqqqqqqq",myDecodedToken);
    console.log(isMyTokenExpired);
    console.log(method);

    switch (method) {
        case 'tokenValidity':
            return isMyTokenExpired;
        case 'getUsername':
            return myDecodedToken.data.username;
        case 'getUserId':
            return myDecodedToken.id;
        case 'getUserUid':
            return myDecodedToken.uid;
        default:
            return;
    }  
};

