import { useEffect, useState } from 'react'
import style from './participantes.module.css'
import { toast } from 'react-toastify';
import Table from 'react-bootstrap/esm/Table.js';
import api from '../../api/api';
import Button from 'react-bootstrap/esm/Button.js';

const Participantes = () => {
    const [participantes, setParticipantes] = useState([]);
    const [rows, setRows] = useState([])
    const [showModal, setShowModal] = useState(false)

    const buscarParticipantes = async () => {
        try {
            const res = await api.get('/participante')


            setParticipantes(res.data.dados)

        } catch (err) {
            toast.error('Erro ao buscar participantes');
        }
    }

    useEffect(() => {
        buscarParticipantes()
        setRows(participantes);
    }, [])

    const columns = [
        { header: 'Nome', accessor: 'nome' },
        { header: 'Cpf', accessor: 'cpf' },
        { header: 'Email', accessor: 'email' },
        { header: 'Telefone', accessor: 'telefone' },
        { header: 'Data de nascimento', accessor: 'data_nascimento' },
    ]

    return (
        <>
            <h1 className={style.titulo}>Participantes</h1>
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
                    {participantes.map(rows => (
                        <tr>
                            {columns.map(columns => (
                                <td key={columns.accessor}>{participantes[columns.accessor]}</td>

                            ))}
                        </tr>
                    ))}
                </tbody>
            </Table>
        </>
    )
}

export default Participantes