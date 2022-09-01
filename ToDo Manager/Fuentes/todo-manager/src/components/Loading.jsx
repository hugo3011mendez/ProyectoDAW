/**
 * Componente referente a un spinner que será usado como animación de carga de datos cuando se realicen peticiones a la API y haya que mostrar datos
 */
const Loading = () => {
    // Declaro un objeto de estilo para el spinner
    const spinnerStyle = {
        width: "15rem",
        height: "15rem"
    }

    return (
        // Pego el elemento de Bootstrap
        <div className="d-flex justify-content-center"> {/* Centro el spinner */}
            <div className="spinner-border" style={spinnerStyle} role="status"> {/* Establezco el estilo del spinner */}
                <span className="visually-hidden">Loading...</span>
            </div>
        </div>
    );
}

export default Loading