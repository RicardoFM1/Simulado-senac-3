import Button from 'react-bootstrap/esm/Button'
import style from './oficinas.module.css'
import api from '../../api/api'
import { useEffect, useState } from 'react'
import Table from 'react-bootstrap/esm/Table'

const Oficinas = () => {

    const [oficinas, setOficinas] = useState([]);
    const buscarOficinas = async () => {
        try{
            const res = await api.get('/oficina');

            if(res.status === 200){
                setOficinas(res.data.dados)
            }
        }catch(err){
            console.log(err)
        }
    }

    useEffect(() => {
        buscarOficinas()
    }, [])

     const columns = [
        { header: 'Id da oficina', accessor: 'id_oficina'},
        { header: 'Título', accessor: 'titulo' },
        { header: 'Descrição', accessor: 'descricao' },
        { header: 'Categoria', accessor: 'categoria' },
        { header: 'Carga horária', accessor: 'carga_horaria' },
        { header: 'Data da oficina', accessor: 'data_oficina' },
        { header: 'Horário', accessor: 'horario' },
        { header: 'Total de vagas', accessor: 'total_vagas' },
        { header: 'Id do instrutor', accessor: 'instrutor_idinstrutor' },


    ]

    return (
        <>
         <h1 className={style.titulo}>Oficinas</h1>
        <Button className={style.button}>Novo</Button>
        <Table responsive bordered hover>
                <thead>
                    <tr>
                        {columns.map(columns => (
                            <th key={columns.accessor}>{columns.header}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                   {oficinas.map(oficina => (

                       <tr>
                            {columns.map(columns => (
                                <td key={columns.accessor}>{oficina[columns.accessor]}</td>

                            ))}
                        </tr>
                    
                        ))}
                </tbody>
            </Table>
        </>
    )
}

export default Oficinas