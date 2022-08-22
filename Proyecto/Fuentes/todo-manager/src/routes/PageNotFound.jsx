import { Link } from "react-router-dom"


const PageNotFound = () => {
  return (
    <div className="container">
        <h1>ERROR : PÁGINA NO ENCONTRADA</h1>
        <h4>Quizás quisiste ir a alguna de estas páginas :</h4>
        <Link to="/main" className="btn btn-warning">Main</Link>
    </div>
  )
}

export default PageNotFound