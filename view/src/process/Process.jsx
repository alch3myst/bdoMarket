import React, { Component } from "react";
import axios from "axios";
import CurrencyFormat from "react-currency-format";

class Process extends Component {
  constructor(props) {
    super(props);

    this.state = {
      processProffit: [],
      searchfor: "",
    };

    this.search = this.search.bind(this);
    this.handleSearch = this.handleSearch.bind(this);
    this.input = React.createRef();
  }

  componentDidMount() {
    // Populate with all results
    axios
      .get("http://localhost:9988/api/v1/process/getProcessProffit.php")
      .then((res) => {
        this.setState({ processProffit: res.data });
      });
  }

  // Search function
  search() {
    if (this.state.searchfor !== "") {
      axios
        .get(
          "http://localhost:9988/api/v1/process/searchProcessProffit.php?recipe=" +
            this.state.searchfor
        )
        .then((res) => {
          this.setState({ processProffit: res.data });
        });
    } else {
      axios
        .get("http://localhost:9988/api/v1/process/getProcessProffit.php")
        .then((res) => {
          this.setState({ processProffit: res.data });
        });
    }
  }

  handleSearch(event) {
    this.setState({ searchfor: event.target.value });
    this.search();
  }

  render() {
    return (
      <div>
        <section className="page-title">
          <h1>Cooking</h1>
          <p>Need some proffit?</p>
        </section>

        <section>
          <span>Search </span>
          <input
            type="text"
            ref={this.input}
            value={this.state.searchfor}
            onChange={this.handleSearch}
            onKeyUp={this.search}
          />
        </section>

        <div className="cooking-list">
          {this.state.processProffit.map((recipe) => (
            <ul key={recipe.name}>
              {/* Recipe name */}
              <li>
                <b>{recipe.name}</b>
              </li>

              {/* Market Price */}
              <li>
                Market:{" "}
                <CurrencyFormat
                  value={recipe.marketPrice | 0}
                  displayType={"text"}
                  thousandSeparator={true}
                  prefix={"$"}
                />
              </li>

              {/* Rare proc market price */}
              {recipe.rareProcMarketPrice !== 0 && (
                <li>
                  Market Rare:{" "}
                  <CurrencyFormat
                    value={recipe.rareProcMarketPrice | 0}
                    displayType={"text"}
                    thousandSeparator={true}
                    prefix={"$"}
                  />
                </li>
              )}

              {/* Recipe cost to make */}
              <li>
                Cost{" "}
                <CurrencyFormat
                  value={Math.round(recipe.cost) | 0}
                  displayType={"text"}
                  thousandSeparator={true}
                  prefix={"$"}
                />
              </li>

              {/* Conditional render of proffit with value pack */}
              {recipe.proffitWPE > 0 ? (
                <li className="proffit">
                  Proffit VP:{" "}
                  <CurrencyFormat
                    value={Math.round(recipe.proffitWPE) | 0}
                    displayType={"text"}
                    thousandSeparator={true}
                    prefix={"$"}
                  />
                </li>
              ) : (
                <li className="loss">
                  Loss VP:{" "}
                  <CurrencyFormat
                    value={Math.round(recipe.proffitWPE) | 0}
                    displayType={"text"}
                    thousandSeparator={true}
                    prefix={"$"}
                  />
                </li>
              )}

              {/* Conditional render of proffit without value pack */}
              {recipe.proffitWTPE > 0 ? (
                // In proffit case
                <li className="proffit">
                  Proffit no VP:{" "}
                  <CurrencyFormat
                    value={Math.round(recipe.proffitWTPE) | 0}
                    displayType={"text"}
                    thousandSeparator={true}
                    prefix={"$"}
                  />
                </li>
              ) : (
                // In loss case
                <li className="loss">
                  Loss no VP:{" "}
                  <CurrencyFormat
                    value={Math.round(recipe.proffitWTPE) | 0}
                    displayType={"text"}
                    thousandSeparator={true}
                    prefix={"$"}
                  />
                </li>
              )}

              {/* Recipe ingredient list */}
              <li className="recipe-li">
                {JSON.parse(recipe.recipe).map((ing) => (
                  /*
                  0 Ingredient name
                  1 Ingredient qtd
                  2 Ingredient base price
                  3 Ingredient precalculated total
                  */
                  <div
                    className="m5-tb"
                    key={"recipe_ingredient_" + Math.random()}
                  >
                    <p>
                      {ing[0]} * {ing[1]}
                    </p>
                    <p>
                      Price:{" "}
                      <CurrencyFormat
                        value={Math.round(ing[2]) | 0}
                        displayType={"text"}
                        thousandSeparator={true}
                        prefix={"$"}
                      />{" "}
                      Total:{" "}
                      <CurrencyFormat
                        value={Math.round(ing[3]) | 0}
                        displayType={"text"}
                        thousandSeparator={true}
                        prefix={"$"}
                      />{" "}
                    </p>
                  </div>
                ))}
              </li>
            </ul>
          ))}
        </div>
      </div>
    );
  }
}

export default Process;
