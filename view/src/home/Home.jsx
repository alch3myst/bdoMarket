import React, { Component } from "react";
import axios from "axios";
import CurrencyFormat from "react-currency-format";
import "./home.css"

class Home extends Component {
  state = {
    cookingTop10: [],
  };

  componentDidMount() {
    axios
      .get("http://localhost:9988/api/v1/cooking/getTop10Cooking.php")
      .then((res) => {
        this.setState({ cookingTop10: res.data });
      });
  }

  render() {
    return (
      <div>
        <section className="page-title">
          <h1>BDO Market Proffit</h1>
          <p>A Proffit Machine</p>
        </section>

        <h2>Top 10 Cooking</h2>
        <div className="top-tier-home-page">
          {this.state.cookingTop10.map((recipe) => (
            <ul key={"recipe_"+Math.random()}>
              <li><b>{recipe.name}</b></li>
              <li>
                Market
                <CurrencyFormat
                  value={recipe.marketPrice}
                  displayType={"text"}
                  thousandSeparator={true}
                  prefix={"$"}
                />
              </li>
              <li>
                Cost
                <CurrencyFormat
                  value={recipe.cost}
                  displayType={"text"}
                  thousandSeparator={true}
                  prefix={"$"}
                />
              </li>
              <li>
                Proffit
                <CurrencyFormat
                  value={Math.round(recipe.proffitWPE)}
                  displayType={"text"}
                  thousandSeparator={true}
                  prefix={"$"}
                />
              </li>
            </ul>
          ))}
        </div>
      </div>
    );
  }
}

export default Home;
