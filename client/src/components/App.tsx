import { useEffect, useState } from 'react'

import Login from './forms/Login';
import Results from './results/Results';

import '../assets/styles/App.css'

function App() {

  const [displayLogged, setDisplayLogged] = useState(false);
  const [userInfo, setUserInfo] = useState(null);

  useEffect(() => {
    const token = document.cookie.split('loggerz-token=')[1];
    if(token !== "undefined" && token !== "" && token !== null && token !== ";" && token !== undefined) {
      fetch(`http://localhost:2345/userHandler.php?action=getUserInfos&token=${token}`, {
        method: 'GET',
      })
      .then(data => data.json())
      .catch(error => console.log(error))
      .then(data => {
        setUserInfo(data);
      })
      setDisplayLogged(true);
    }
  }, [displayLogged]);

  return (<>
  <div className="App lg:h-screen min-h-screen w-screen flex item-center m-auto bg-color-1 py-5">
      {displayLogged ? <Results setDisplayLogged={setDisplayLogged} userInfo={userInfo} /> :  <Login displayLogged={displayLogged}  setDisplayLogged={setDisplayLogged} />}
    </div></>)
}

export default App
