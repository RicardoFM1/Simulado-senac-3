import Button from 'react-bootstrap/esm/Button'
import style from './oficinas.module.css'

const Oficinas = () => {
    return (
        <>
         <h1 className={style.titulo}>Oficinas</h1>
        <Button className={style.button}>Novo</Button>
        </>
    )
}

export default Oficinas