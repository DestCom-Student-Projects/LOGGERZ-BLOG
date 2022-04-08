import { useState } from "react";
import CreatePost from "../forms/CreatePost";
import Header from "../Header";
import PostContainer from "../posts/PostContainer";

type AppProps = {
    setDisplayLogged: any;
    userInfo:any;
  };

const Results = ({setDisplayLogged, userInfo}: AppProps) =>{

    const [updateTrigger, setUpdateTrigger] = useState(null)

    const disconnect = () => {
        document.cookie = `loggerz-token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
        setDisplayLogged(false);
    }

    return <div className="lg:w-11/12 w-11/12 m-auto bg-gray-800 shadow-2xl rounded-lg overflow-hidden lg:max-h-vp11/12">
                <div className="py-3 px-4 flex">
                    <div className="rounded-full w-3 h-3 bg-red-500 mr-2 hover:cursor-pointer" onClick={()=> disconnect()}></div>
                    <div className="rounded-full w-3 h-3 bg-yellow-500 mr-2"></div>
                    <div className="rounded-full w-3 h-3 bg-green-500"></div>
                    <p className="text-gray-600 h-3 text-xs italic ml-3">{"(<--- Click on red dot to disconnect)"}</p>
                </div>
                <Header />
                <div className='m-auto text-gray-500 text-xl flex flex-col lg:flex-row lg:justify-between lg:w-11/12 lg:max-h-[85vh] lg:min-h-[80vh] mb-2'>
                <CreatePost setUpdateTrigger={setUpdateTrigger} userInfo={userInfo} />
                <PostContainer updateTrigger={updateTrigger} />
                </div>
            </div>
}

export default Results;