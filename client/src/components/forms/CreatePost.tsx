import { useState } from "react";
import { getCookie } from "../../assets/functions/GetCookie";

type AppProps = {
    setUpdateTrigger: any;
    userInfo:any;
};

const CreatePost = ({setUpdateTrigger, userInfo}:AppProps) => {
    const [title, setTitle] = useState<string | null>(null);
    const [content, setContent] = useState<string | null>(null);

    const sendPost = (e: any) => {
        e.preventDefault();
        if (title === null || content === null) return;

        const token = getCookie("loggerz-token");

        if(token === null || token === undefined || token === "undefined") return;

        fetch("http://localhost:2345/postHandler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ title: title, content: content, author_uid: token})
        })
        .then(res => res.json())
        .catch(error => console.error("Error:", error))
        .then(data => {
            console.log(data);
            setUpdateTrigger(Date.now().toString());
        });

    };

    return ( 
    <div className="py-4 px-4 m-auto text-white text-xl w-1/2 flex flex-col items-center">
        {userInfo &&  <p className="text-gray-600 h-3 text-base italic my-3">Logged as {userInfo.username}</p>}
        <input type={'text'} className="outline-none bg-transparent placeholder:text-gray-500 text-gray-500 border-2 border-gray-500 rounded my-2 mx-auto p-2 min-w-full" placeholder="The awesome title !" onChange={(e) => setTitle(e.target.value)}  />
        <textarea  className="outline-none bg-transparent placeholder:text-gray-500 text-gray-500 border-2 border-gray-500 rounded my-2 mx-auto p-2 min-w-full" placeholder="The awesome content !" onChange={(e) => setContent(e.target.value)} />
        <div onClick={(e)=>sendPost(e)} className={`w-1/2% m-auto bg-color-1 text-lg rounded my-2 text-gray-400 py-2 text-center ${(title && content)? "hover:bg-color-2 cursor-pointer" : "cursor-not-allowed"}`}>Upload my awesome post</div>
    </div>)
}

export default CreatePost;