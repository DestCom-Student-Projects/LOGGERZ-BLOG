import { useEffect, useState } from 'react'
import axios from 'axios';

import Login from './forms/Login';
import Results from './results/Results';
import { UserType } from '../assets/helpers/Users';
import { axiosInstance } from '../assets/helpers/AxiosInstances';
import { JWTHandler } from '../assets/helpers/JWTHandler';

import '../assets/styles/App.css'
import { getCookie } from '../assets/functions/GetCookie';

function App() {

  const [displayLogged, setDisplayLogged] = useState<boolean>(false);
  const [userInfo, setUserInfo] = useState< UserType | null>(null);

  useEffect(() => {
    const token = document.cookie.split('loggerz-token=')[1];
    if(token !== "undefined" && token !== "" && token !== null && token !== ";" && token !== undefined) {
      console.log(token);
      let json = {
        username: JWTHandler({token, method: "getUsername"})
      }
      setUserInfo(json);
      setDisplayLogged(true);
    }
  }, [displayLogged]);

  return (<>
  <div className="App lg:h-screen min-h-screen w-screen flex item-center m-auto bg-color-1 py-5">
      {displayLogged ? <Results setDisplayLogged={setDisplayLogged} userInfo={userInfo!} /> :  <Login displayLogged={displayLogged}  setDisplayLogged={setDisplayLogged} />}
    </div></>)
}

export default App
