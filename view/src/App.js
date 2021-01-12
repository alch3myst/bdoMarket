import Home from "./home/Home";
import Cooking from "./cooking/Cooking";
import "./components/main.css";
import Process from "./process/Process";
import Alchemy from "./alchemy/Alchemy";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";

// Make routing here

function App() {
  return (
    <Router>
      <div className="App">
        <div className="top-bg"></div>
        <ul className="app-top-menu">
          <h3>BDOM</h3>
          <Link to="/">Home</Link>
          <Link to="/cooking">Cooking</Link>
          <Link to="/process">Process</Link>
          <Link to="/alchemy">Alchemy</Link>
          <a href="http://localhost:9988/api/v1/update.php" target="_blank" rel="noopener noreferrer">Update</a>
        </ul>

        <Switch>
          <Route path="/alchemy">
            <Alchemy />
          </Route>
          <Route path="/process">
            <Process />
          </Route>
          <Route path="/cooking">
            <Cooking />
          </Route>
          <Route path="/">
            <Home />
          </Route>
        </Switch>
      </div>
    </Router>
  );
}

export default App;
