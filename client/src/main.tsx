import ReactDOM from 'react-dom'
import { BrowserRouter } from "react-router-dom";
import './assets/styles/index.css'
import App from './components/App'

ReactDOM.render(
  <BrowserRouter>
    <App />
  </BrowserRouter>,
  document.getElementById('root')
)
