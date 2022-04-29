import { useEffect, useState } from 'react'

import Login from './forms/Login';
import Results from './results/Results';
import { UserType } from '../assets/helpers/Users';

import { JWTHandler } from '../assets/helpers/JWTHandler';
import { Routes, Route, useNavigate } from "react-router-dom";

import '../assets/styles/App.css'

function App() {

  const navigate = useNavigate();
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

      if(displayLogged){
        navigate("/logged");
      }
    }
  }, [ displayLogged ]);

  return (<>
  <div className="App lg:h-screen min-h-screen w-screen flex item-center m-auto bg-color-1 py-5">
      <Routes>
        <Route path="/" element={<Login displayLogged={displayLogged}  setDisplayLogged={setDisplayLogged} />} />
        <Route path="/logged" element={<Results setDisplayLogged={setDisplayLogged} userInfo={userInfo!} displayLogged={displayLogged} />} />
      </Routes>
      {/* {displayLogged ? <Results setDisplayLogged={setDisplayLogged} userInfo={userInfo!} /> :  <Login displayLogged={displayLogged}  setDisplayLogged={setDisplayLogged} />} */}
    </div></>)
}

export default App
